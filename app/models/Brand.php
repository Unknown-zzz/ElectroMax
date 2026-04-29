<?php
class Brand {
    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function all(): array {
        return $this->db->call('sp_marcas_get_all')->fetchAll();
    }

    public function create(string $nombre): void {
        $this->db->call('sp_marca_create', [$nombre]);
    }

    public function update(int $id, string $nombre): void {
        $this->db->call('sp_marca_update', [$id, $nombre]);
    }

    public function delete(int $id): void {
        $this->db->call('sp_marca_delete', [$id]);
    }
}
