<?php
class Database {
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct() {
        $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $this->pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function query(string $sql, array $params = []): PDOStatement {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function call(string $procedure, array $params = []): PDOStatement {
        $placeholders = implode(',', array_fill(0, count($params), '?'));
        $sql = "CALL {$procedure}(" . ($placeholders ?: '') . ")";
        return $this->query($sql, $params);
    }

    public function callOut(string $procedure, array $inParams, array $outNames): array {
        $db = $this->pdo;
        $inPlaceholders = implode(',', array_fill(0, count($inParams), '?'));
        $outPlaceholders = implode(',', array_map(fn($n) => "@{$n}", $outNames));
        $sep = ($inPlaceholders && $outPlaceholders) ? ',' : '';
        $db->prepare("CALL {$procedure}({$inPlaceholders}{$sep}{$outPlaceholders})")
           ->execute($inParams);
        $outSelect = implode(',', array_map(fn($n) => "@{$n} AS {$n}", $outNames));
        return $db->query("SELECT {$outSelect}")->fetch();
    }

    public function lastInsertId(): string {
        return $this->pdo->lastInsertId();
    }

    public function beginTransaction(): void { $this->pdo->beginTransaction(); }
    public function commit(): void           { $this->pdo->commit(); }
    public function rollback(): void         { $this->pdo->rollBack(); }
}
