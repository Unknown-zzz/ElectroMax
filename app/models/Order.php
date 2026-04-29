<?php
class Order {
    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create(array $d): int {
        $result = $this->db->callOut(
            'sp_pedido_create',
            [$d['usuario_id'], $d['total'], $d['nombre_cliente'],
             $d['email_cliente'], $d['telefono'], $d['direccion'], $d['notas']],
            ['p_pedido_id']
        );
        return (int)$result['p_pedido_id'];
    }

    public function addDetail(int $pedidoId, int $productoId, int $cantidad, float $precio): void {
        $this->db->call('sp_pedido_detalle_add', [$pedidoId, $productoId, $cantidad, $precio]);
    }

    public function all(): array {
        return $this->db->call('sp_pedidos_get_all')->fetchAll();
    }

    public function byUsuario(int $userId): array {
        return $this->db->call('sp_pedidos_by_usuario', [$userId])->fetchAll();
    }

    public function getDetalle(int $pedidoId): array {
        return $this->db->call('sp_pedido_get_detalle', [$pedidoId])->fetchAll();
    }

    public function updateEstado(int $pedidoId, string $estado): void {
        $this->db->call('sp_pedido_update_estado', [$pedidoId, $estado]);
    }
}
