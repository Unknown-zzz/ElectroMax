<?php
/**
 * Script para verificar mensajes guardados en chat
 * Ejecutar: php check-messages.php
 */

try {
    $db = new PDO(
        'mysql:host=127.0.0.1;port=3309;dbname=electromax;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "=== Mensajes de Canales ===\n";
    $stmt = $db->query(
        'SELECT cm.id, cm.canal_id, cm.nombre_usuario, cm.contenido, cm.ts,
                cc.nombre as canal_nombre
         FROM chat_mensajes cm
         JOIN chat_canales cc ON cc.id = cm.canal_id
         ORDER BY cm.ts DESC
         LIMIT 20'
    );

    $mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($mensajes)) {
        echo "⚠ No hay mensajes guardados en la base de datos\n";
    } else {
        echo "✓ Total de últimos 20 mensajes:\n";
        foreach ($mensajes as $msg) {
            $fecha = date('Y-m-d H:i:s', (int)($msg['ts'] / 1000));
            echo "[{$msg['canal_nombre']}] {$msg['nombre_usuario']}: {$msg['contenido']}\n";
            echo "  Fecha: {$fecha} (ts: {$msg['ts']})\n";
        }
    }

    echo "\n=== Mensajes Privados ===\n";
    $stmt = $db->query(
        'SELECT id, nombre_remitente, contenido, ts
         FROM chat_privados
         ORDER BY ts DESC
         LIMIT 10'
    );

    $privados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($privados)) {
        echo "⚠ No hay mensajes privados\n";
    } else {
        echo "✓ Total de últimos 10 DMs:\n";
        foreach ($privados as $msg) {
            $fecha = date('Y-m-d H:i:s', (int)($msg['ts'] / 1000));
            echo "{$msg['nombre_remitente']}: {$msg['contenido']}\n";
            echo "  Fecha: {$fecha}\n";
        }
    }

    echo "\n=== Estadísticas ===\n";
    $stmt = $db->query('SELECT COUNT(*) as total FROM chat_mensajes');
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total mensajes de canales: {$result['total']}\n";

    $stmt = $db->query('SELECT COUNT(*) as total FROM chat_privados');
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total mensajes privados: {$result['total']}\n";

} catch (Exception $e) {
    echo "✗ Error: {$e->getMessage()}\n";
    exit(1);
}
