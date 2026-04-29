<?php
class Category {
    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function all(): array {
        return $this->db->call('sp_categorias_get_all')->fetchAll();
    }

    public function create(string $nombre, string $descripcion, string $icono): void {
        $this->db->call('sp_categoria_create', [$nombre, $descripcion, $icono]);
    }

    public function update(int $id, string $nombre, string $descripcion, string $icono): void {
        $this->db->call('sp_categoria_update', [$id, $nombre, $descripcion, $icono]);
    }

    public function delete(int $id): void {
        $this->db->call('sp_categoria_delete', [$id]);
    }
}
