<?php
/**
 * Script para verificar y configurar los permisos de chat
 * Ejecutar: php setup-permissions.php
 */

require_once __DIR__ . '/../app/config/Database.php';

try {
    // Conectar a la base de datos
    $db = new PDO(
        'mysql:host=127.0.0.1;port=3309;dbname=electromax;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "✓ Conectado a la base de datos\n\n";

    // Verificar canales
    echo "=== Canales ===\n";
    $stmt = $db->query('SELECT id, nombre FROM chat_canales ORDER BY id');
    $canales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($canales as $canal) {
        echo "[{$canal['id']}] {$canal['nombre']}\n";
    }

    echo "\n=== Permisos Actuales ===\n";
    $stmt = $db->query(
        'SELECT cr.canal_id, c.nombre, cr.rol, cr.puede_escribir
         FROM chat_canal_roles cr
         JOIN chat_canales c ON c.id = cr.canal_id
         ORDER BY cr.canal_id, cr.rol'
    );
    $permisos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($permisos)) {
        echo "⚠ No hay permisos configurados. Insertando...\n";

        // Insertar permisos predeterminados
        $inserts = [
            // general — todos leen y escriben
            [1, 'admin', 1],
            [1, 'vendedor', 1],
            [1, 'inventario', 1],

            // avisos — todos leen, solo admin escribe
            [2, 'admin', 1],
            [2, 'vendedor', 0],
            [2, 'inventario', 0],

            // ventas — admin y vendedores leen y escriben
            [3, 'admin', 1],
            [3, 'vendedor', 1],

            // inventario — admin e inventario leen y escriben
            [4, 'admin', 1],
            [4, 'inventario', 1],
        ];

        $stmt = $db->prepare(
            'INSERT IGNORE INTO chat_canal_roles (canal_id, rol, puede_escribir) VALUES (?,?,?)'
        );

        foreach ($inserts as [$canalId, $rol, $puedeEscribir]) {
            $stmt->execute([$canalId, $rol, $puedeEscribir]);
            echo "✓ Insertado: Canal {$canalId} - Rol {$rol} - Escribir: {$puedeEscribir}\n";
        }
    } else {
        foreach ($permisos as $p) {
            $escribir = $p['puede_escribir'] ? 'SÍ' : 'NO';
            echo "[{$p['canal_id']}] {$p['nombre']} - {$p['rol']}: Escribir={$escribir}\n";
        }
    }

    echo "\n✓ Configuración de permisos completada\n";

} catch (Exception $e) {
    echo "✗ Error: {$e->getMessage()}\n";
    exit(1);
}
