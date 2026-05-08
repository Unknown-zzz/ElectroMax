<?php
/**
 * ElectroMax Chat Server (Ratchet)
 *
 * Uso:
 * php ratchet-server.php
 *
 * Variables de entorno:
 * - WS_PORT: Puerto del servidor WebSocket (default: 8181)
 * - DB_HOST: Host de la base de datos (default: 127.0.0.1)
 * - DB_PORT: Puerto de la base de datos (default: 3309)
 * - DB_NAME: Nombre de la base de datos (default: electromax)
 * - DB_USER: Usuario de la base de datos (default: root)
 * - DB_PASS: Contraseña de la base de datos (default: vacía)
 */

require_once __DIR__ . '/vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use ElectroMax\Chat\ChatHandler;

// ─── Config ─────────────────────────────────────────────────────────────
$wsPort = (int)(getenv('WS_PORT') ?: 8181);
$dbHost = getenv('DB_HOST') ?: '127.0.0.1';
$dbPort = (int)(getenv('DB_PORT') ?: 3309);
$dbName = getenv('DB_NAME') ?: 'electromax';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: '';

// ─── DB Connection ──────────────────────────────────────────────────────
try {
    $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
    $db = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    echo "[DB] Conexión establecida.\n";
} catch (PDOException $e) {
    echo "[DB Error] " . $e->getMessage() . "\n";
    exit(1);
}

// ─── Server ─────────────────────────────────────────────────────────────
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ChatHandler($db)
        )
    ),
    $wsPort,
    '0.0.0.0'
);

echo "╔════════════════════════════════════════╗\n";
echo "║  ElectroMax Chat Server (Ratchet)      ║\n";
echo "║  ws://0.0.0.0:{$wsPort}                 ║\n";
echo "╚════════════════════════════════════════╝\n\n";

$server->run();
