<?php
class User {
    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function register(string $nombre, string $email, string $password, string $telefono): array {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        return $this->db->callOut('sp_usuario_crear', [$nombre, $email, $hash, $telefono], ['p_id', 'p_error']);
    }

    public function findByEmail(string $email): ?array {
        $row = $this->db->call('sp_usuario_login', [$email])->fetch();
        return $row ?: null;
    }

    public function findById(int $id): ?array {
        $row = $this->db->call('sp_usuario_get_by_id', [$id])->fetch();
        return $row ?: null;
    }

    public function login(string $email, string $password): ?array {
        $user = $this->findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) return null;
        if (!$user['activo']) return null;
        return $user;
    }
}
