USE electromax;

-- Extender el ENUM de roles para incluir staff
ALTER TABLE usuarios MODIFY COLUMN rol ENUM('cliente','admin','vendedor','inventario') DEFAULT 'cliente';

-- ── Tablas de Chat ──────────────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS chat_canales (
    id     INT AUTO_INCREMENT PRIMARY KEY,
    slug   VARCHAR(50)  UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255) DEFAULT '',
    icono  VARCHAR(10)  DEFAULT '#',
    tipo   ENUM('publico','anuncios') DEFAULT 'publico',
    orden  INT DEFAULT 0
);

-- Qué roles pueden acceder (leer) y escribir en cada canal
CREATE TABLE IF NOT EXISTS chat_canal_roles (
    canal_id       INT NOT NULL,
    rol            ENUM('admin','vendedor','inventario') NOT NULL,
    puede_escribir TINYINT(1) DEFAULT 1,
    PRIMARY KEY (canal_id, rol),
    FOREIGN KEY (canal_id) REFERENCES chat_canales(id) ON DELETE CASCADE
);

-- Mensajes de canal
CREATE TABLE IF NOT EXISTS chat_mensajes (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    canal_id       INT          NOT NULL,
    usuario_id     INT          NOT NULL,
    nombre_usuario VARCHAR(100) NOT NULL,
    rol_usuario    ENUM('admin','vendedor','inventario') NOT NULL,
    contenido      TEXT         NOT NULL,
    ts             BIGINT       NOT NULL,
    FOREIGN KEY (canal_id)   REFERENCES chat_canales(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)     ON DELETE CASCADE
);

-- Mensajes privados entre empleados
CREATE TABLE IF NOT EXISTS chat_privados (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    id_remitente     INT          NOT NULL,
    nombre_remitente VARCHAR(100) NOT NULL,
    id_destinatario  INT          NOT NULL,
    contenido        TEXT         NOT NULL,
    ts               BIGINT       NOT NULL,
    FOREIGN KEY (id_remitente)    REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_destinatario) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tokens de sesión de un solo uso para autenticar el WebSocket
CREATE TABLE IF NOT EXISTS chat_tokens (
    token      VARCHAR(64) PRIMARY KEY,
    usuario_id INT         NOT NULL,
    expires_at BIGINT      NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- ── Canales predeterminados ─────────────────────────────────────────────────

INSERT IGNORE INTO chat_canales (id, slug, nombre, descripcion, icono, tipo, orden) VALUES
(1, 'general',    'general',    'Canal general para todo el equipo',          '💬', 'publico',  1),
(2, 'avisos',     'avisos',     'Avisos y comunicados del administrador',     '📢', 'anuncios', 2),
(3, 'ventas',     'ventas',     'Canal del equipo de ventas',                 '🛒', 'publico',  3),
(4, 'inventario', 'inventario', 'Canal del equipo de inventario y almacén',   '📦', 'publico',  4);

-- general — todos leen y escriben
INSERT IGNORE INTO chat_canal_roles VALUES (1,'admin',1),(1,'vendedor',1),(1,'inventario',1);

-- avisos — todos leen, solo admin escribe
INSERT IGNORE INTO chat_canal_roles VALUES (2,'admin',1),(2,'vendedor',0),(2,'inventario',0);

-- ventas — admin y vendedores
INSERT IGNORE INTO chat_canal_roles VALUES (3,'admin',1),(3,'vendedor',1);

-- inventario — admin e inventario
INSERT IGNORE INTO chat_canal_roles VALUES (4,'admin',1),(4,'inventario',1);
