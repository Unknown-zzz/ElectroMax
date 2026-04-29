<?php
class Product {
    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function all(): array {
        return $this->db->call('sp_productos_get_all')->fetchAll();
    }

    public function allAdmin(): array {
        return $this->db->call('sp_productos_admin_all')->fetchAll();
    }

    public function find(int $id): ?array {
        $row = $this->db->call('sp_producto_get_by_id', [$id])->fetch();
        return $row ?: null;
    }

    public function byCategoria(int $catId): array {
        return $this->db->call('sp_productos_by_categoria', [$catId])->fetchAll();
    }

    public function destacados(): array {
        return $this->db->call('sp_productos_destacados')->fetchAll();
    }

    public function buscar(string $query): array {
        return $this->db->call('sp_productos_buscar', [$query])->fetchAll();
    }

    public function create(array $d): void {
        $this->db->call('sp_producto_create', [
            $d['nombre'], $d['descripcion'], $d['precio'], $d['precio_oferta'] ?: null,
            $d['stock'], $d['imagen'] ?? null, $d['categoria_id'], $d['marca_id'], $d['destacado']
        ]);
    }

    public function update(int $id, array $d): void {
        $this->db->call('sp_producto_update', [
            $id, $d['nombre'], $d['descripcion'], $d['precio'], $d['precio_oferta'] ?: null,
            $d['stock'], $d['imagen'] ?? null, $d['categoria_id'], $d['marca_id'], $d['destacado']
        ]);
    }

    public function delete(int $id): void {
        $this->db->call('sp_producto_delete', [$id]);
    }
}
