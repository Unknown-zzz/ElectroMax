<?php
namespace ElectroMax\Chat;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use PDO;

class ChatHandler implements MessageComponentInterface {
    protected \SplObjectStorage $clients;
    protected PDO $db;
    protected array $clientUsers = [];

    public function __construct(PDO $db) {
        $this->clients = new \SplObjectStorage();
        $this->db = $db;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "[+] Nueva conexión. Total: {$this->clients->count()}\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        try {
            $data = json_decode($msg, true);
            if (!is_array($data) || !isset($data['type'])) return;

            match ($data['type']) {
                'auth'       => $this->handleAuth($from, $data),
                'msg_canal'  => $this->handleMsgCanal($from, $data),
                'msg_dm'     => $this->handleMsgDm($from, $data),
                'hist_canal' => $this->handleHistCanal($from, $data),
                'hist_dm'    => $this->handleHistDm($from, $data),
                'bye'        => $from->close(),
                default      => null
            };
        } catch (\Exception $e) {
            echo "[Error] {$e->getMessage()}\n";
        }
    }

    public function onClose(ConnectionInterface $conn) {
        if (isset($this->clientUsers[$conn->resourceId])) {
            $usuario = $this->clientUsers[$conn->resourceId];
            echo "[bye] {$usuario['nombre']}\n";
            unset($this->clientUsers[$conn->resourceId]);
            $this->broadcastPresencia();
        }
        $this->clients->detach($conn);
        echo "[-] Conexión cerrada. Total: {$this->clients->count()}\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "[Error WS] {$e->getMessage()}\n";
        $conn->close();
    }

    // ─── Auth ───────────────────────────────────────────────────────────────
    private function handleAuth(ConnectionInterface $conn, array $data) {
        $token = $data['token'] ?? '';
        $usuario = $this->validarToken($token);

        if (!$usuario) {
            $this->send($conn, ['type' => 'auth_err', 'texto' => 'Token inválido o expirado.']);
            $conn->close();
            return;
        }

        $this->clientUsers[$conn->resourceId] = $usuario;

        $canales = $this->canalesDeRol($usuario['rol']);
        $staff = $this->todosStaff();
        $online = $this->onlineIds();

        $this->send($conn, [
            'type' => 'auth_ok',
            'usuario' => $usuario,
            'canales' => $canales,
            'staff' => $staff,
            'online' => $online
        ]);

        echo "[auth] {$usuario['nombre']} ({$usuario['rol']})\n";
        $this->broadcastPresencia();
    }

    private function validarToken(string $token): ?array {
        if (empty($token)) return null;

        $now = (int)(microtime(true) * 1000);
        $stmt = $this->db->prepare(
            'SELECT ct.usuario_id, u.nombre, u.rol FROM chat_tokens ct ' .
            'JOIN usuarios u ON u.id = ct.usuario_id ' .
            'WHERE ct.token = ? AND ct.expires_at >= ? AND u.activo = 1'
        );
        $stmt->execute([$token, $now]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        // Consume token (one-time use)
        $delStmt = $this->db->prepare('DELETE FROM chat_tokens WHERE token = ?');
        $delStmt->execute([$token]);

        return [
            'id' => (int)$row['usuario_id'],
            'nombre' => $row['nombre'],
            'rol' => $row['rol']
        ];
    }

    // ─── Channel Messages ────────────────────────────────────────────────────
    private function handleMsgCanal(ConnectionInterface $conn, array $data) {
        $usuario = $this->clientUsers[$conn->resourceId] ?? null;
        if (!$usuario) return;

        $texto = trim($data['texto'] ?? '');
        $canalId = (int)($data['canal_id'] ?? 0);

        if (empty($texto) || $canalId === 0) return;

        $acceso = $this->tieneAcceso($usuario['id'], $canalId);
        if (!$acceso || !$acceso['puede_escribir']) return;

        $ts = (int)(microtime(true) * 1000);
        $this->guardarMsgCanal($canalId, $usuario['id'], $usuario['nombre'], $usuario['rol'], $texto, $ts);

        $pkg = [
            'type' => 'msg_canal',
            'canal_id' => $canalId,
            'id_rem' => $usuario['id'],
            'nombre' => $usuario['nombre'],
            'rol' => $usuario['rol'],
            'texto' => $texto,
            'ts' => $ts
        ];

        // Broadcast to users with access to this channel
        $rolesConAcceso = $this->rolesConAccesoCanal($canalId);
        foreach ($this->clients as $client) {
            if (isset($this->clientUsers[$client->resourceId])) {
                $clientRol = $this->clientUsers[$client->resourceId]['rol'];
                if (in_array($clientRol, $rolesConAcceso)) {
                    $this->send($client, $pkg);
                }
            }
        }

        echo "[canal#{$canalId}] {$usuario['nombre']}: " . substr($texto, 0, 50) . "\n";
    }

    private function handleMsgDm(ConnectionInterface $conn, array $data) {
        $usuario = $this->clientUsers[$conn->resourceId] ?? null;
        if (!$usuario) return;

        $texto = trim($data['texto'] ?? '');
        $idDest = (int)($data['id_dest'] ?? 0);

        if (empty($texto) || $idDest === 0 || $idDest === $usuario['id']) return;

        $ts = (int)(microtime(true) * 1000);
        $this->guardarMsgDm($usuario['id'], $usuario['nombre'], $idDest, $texto, $ts);

        $pkg = [
            'type' => 'msg_dm',
            'id_rem' => $usuario['id'],
            'nombre' => $usuario['nombre'],
            'rol' => $usuario['rol'],
            'id_dest' => $idDest,
            'texto' => $texto,
            'ts' => $ts
        ];

        // Send to recipient if online
        $destConn = $this->clientePorId($idDest);
        if ($destConn) {
            $this->send($destConn, $pkg);
        }

        // Echo to sender
        $this->send($conn, $pkg);

        echo "[dm] {$usuario['nombre']} -> #{$idDest}\n";
    }

    // ─── History ────────────────────────────────────────────────────────────
    private function handleHistCanal(ConnectionInterface $conn, array $data) {
        $usuario = $this->clientUsers[$conn->resourceId] ?? null;
        if (!$usuario) return;

        $canalId = (int)($data['canal_id'] ?? 0);
        $acceso = $this->tieneAcceso($usuario['id'], $canalId);

        if (!$acceso) return;

        $mensajes = $this->historialCanal($canalId);
        $this->send($conn, [
            'type' => 'hist_canal',
            'canal_id' => $canalId,
            'mensajes' => $mensajes
        ]);
    }

    private function handleHistDm(ConnectionInterface $conn, array $data) {
        $usuario = $this->clientUsers[$conn->resourceId] ?? null;
        if (!$usuario) return;

        $idDest = (int)($data['id_dest'] ?? 0);
        if ($idDest === 0) return;

        $mensajes = $this->historialDm($usuario['id'], $idDest);
        $this->send($conn, [
            'type' => 'hist_dm',
            'id_dest' => $idDest,
            'mensajes' => $mensajes
        ]);
    }

    // ─── DB Helpers ─────────────────────────────────────────────────────────
    private function canalesDeRol(string $rol): array {
        $stmt = $this->db->prepare(
            'SELECT c.id, c.slug, c.nombre, c.descripcion, c.icono, c.tipo, cr.puede_escribir ' .
            'FROM chat_canales c ' .
            'JOIN chat_canal_roles cr ON cr.canal_id = c.id AND cr.rol = ? ' .
            'ORDER BY c.orden'
        );
        $stmt->execute([$rol]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function todosStaff(): array {
        $stmt = $this->db->query(
            "SELECT id, nombre, rol FROM usuarios WHERE rol != 'cliente' AND activo = 1 ORDER BY nombre"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function tieneAcceso(int $userId, int $canalId): ?array {
        $stmt = $this->db->prepare(
            'SELECT cr.puede_escribir FROM chat_canal_roles cr ' .
            'JOIN usuarios u ON u.id = ? AND u.rol = cr.rol ' .
            'WHERE cr.canal_id = ?'
        );
        $stmt->execute([$userId, $canalId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function rolesConAccesoCanal(int $canalId): array {
        $stmt = $this->db->prepare(
            'SELECT rol FROM chat_canal_roles WHERE canal_id = ?'
        );
        $stmt->execute([$canalId]);
        return array_map(fn($r) => $r['rol'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    private function guardarMsgCanal(int $canalId, int $userId, string $nombre, string $rol, string $contenido, int $ts) {
        $stmt = $this->db->prepare(
            'INSERT INTO chat_mensajes (canal_id, usuario_id, nombre_usuario, rol_usuario, contenido, ts) VALUES (?,?,?,?,?,?)'
        );
        $stmt->execute([$canalId, $userId, $nombre, $rol, $contenido, $ts]);
    }

    private function guardarMsgDm(int $idRem, string $nombreRem, int $idDest, string $contenido, int $ts) {
        $stmt = $this->db->prepare(
            'INSERT INTO chat_privados (id_remitente, nombre_remitente, id_destinatario, contenido, ts) VALUES (?,?,?,?,?)'
        );
        $stmt->execute([$idRem, $nombreRem, $idDest, $contenido, $ts]);
    }

    private function historialCanal(int $canalId, int $limite = 80): array {
        $stmt = $this->db->prepare(
            'SELECT * FROM (SELECT * FROM chat_mensajes WHERE canal_id = ? ORDER BY ts DESC LIMIT ' . (int)$limite . ') t ORDER BY ts ASC'
        );
        $stmt->execute([$canalId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($r) => [
            'canal_id' => $r['canal_id'],
            'id_rem' => $r['usuario_id'],
            'nombre' => $r['nombre_usuario'],
            'rol' => $r['rol_usuario'],
            'texto' => $r['contenido'],
            'ts' => (int)$r['ts']
        ], $rows);
    }

    private function historialDm(int $a, int $b, int $limite = 80): array {
        $stmt = $this->db->prepare(
            'SELECT * FROM (SELECT * FROM chat_privados WHERE (id_remitente=? AND id_destinatario=?) OR (id_remitente=? AND id_destinatario=?) ORDER BY ts DESC LIMIT ' . (int)$limite . ') t ORDER BY ts ASC'
        );
        $stmt->execute([$a, $b, $b, $a]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($r) => [
            'id_rem' => $r['id_remitente'],
            'nombre' => $r['nombre_remitente'],
            'id_dest' => $r['id_destinatario'],
            'texto' => $r['contenido'],
            'ts' => (int)$r['ts']
        ], $rows);
    }

    // ─── Broadcast ──────────────────────────────────────────────────────────
    private function onlineIds(): array {
        return array_values(array_map(
            fn($u) => $u['id'],
            array_filter($this->clientUsers)
        ));
    }

    private function clientePorId(int $userId): ?ConnectionInterface {
        foreach ($this->clientUsers as $resourceId => $usuario) {
            if ($usuario['id'] === $userId) {
                foreach ($this->clients as $conn) {
                    if ($conn->resourceId === $resourceId) {
                        return $conn;
                    }
                }
            }
        }
        return null;
    }

    private function broadcastPresencia() {
        $staff = $this->todosStaff();
        $online = $this->onlineIds();
        $pkg = ['type' => 'presencia', 'staff' => $staff, 'online' => $online];

        foreach ($this->clients as $conn) {
            if (isset($this->clientUsers[$conn->resourceId])) {
                $this->send($conn, $pkg);
            }
        }
    }

    private function send(ConnectionInterface $conn, array $data) {
        $conn->send(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}
