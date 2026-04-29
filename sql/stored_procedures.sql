USE electromax;

DROP PROCEDURE IF EXISTS sp_productos_get_all;
DROP PROCEDURE IF EXISTS sp_producto_get_by_id;
DROP PROCEDURE IF EXISTS sp_productos_by_categoria;
DROP PROCEDURE IF EXISTS sp_productos_destacados;
DROP PROCEDURE IF EXISTS sp_productos_buscar;
DROP PROCEDURE IF EXISTS sp_productos_admin_all;
DROP PROCEDURE IF EXISTS sp_producto_create;
DROP PROCEDURE IF EXISTS sp_producto_update;
DROP PROCEDURE IF EXISTS sp_producto_delete;
DROP PROCEDURE IF EXISTS sp_categorias_get_all;
DROP PROCEDURE IF EXISTS sp_categoria_create;
DROP PROCEDURE IF EXISTS sp_categoria_update;
DROP PROCEDURE IF EXISTS sp_categoria_delete;
DROP PROCEDURE IF EXISTS sp_marcas_get_all;
DROP PROCEDURE IF EXISTS sp_marca_create;
DROP PROCEDURE IF EXISTS sp_marca_update;
DROP PROCEDURE IF EXISTS sp_marca_delete;
DROP PROCEDURE IF EXISTS sp_usuario_crear;
DROP PROCEDURE IF EXISTS sp_usuario_login;
DROP PROCEDURE IF EXISTS sp_usuario_get_by_id;
DROP PROCEDURE IF EXISTS sp_pedido_create;
DROP PROCEDURE IF EXISTS sp_pedido_detalle_add;
DROP PROCEDURE IF EXISTS sp_pedidos_get_all;
DROP PROCEDURE IF EXISTS sp_pedidos_by_usuario;
DROP PROCEDURE IF EXISTS sp_pedido_get_detalle;
DROP PROCEDURE IF EXISTS sp_pedido_update_estado;
DROP PROCEDURE IF EXISTS sp_admin_stats;

DELIMITER //

CREATE PROCEDURE sp_productos_get_all()
BEGIN
    SELECT p.id, p.nombre, p.descripcion, p.precio, p.precio_oferta, p.stock,
           p.imagen, p.destacado, p.activo, p.created_at,
           c.nombre AS categoria_nombre, c.id AS categoria_id,
           m.nombre AS marca_nombre, m.id AS marca_id
    FROM productos p
    LEFT JOIN categorias c ON p.categoria_id = c.id
    LEFT JOIN marcas m ON p.marca_id = m.id
    WHERE p.activo = 1
    ORDER BY p.created_at DESC;
END //

CREATE PROCEDURE sp_producto_get_by_id(IN p_id INT)
BEGIN
    SELECT p.id, p.nombre, p.descripcion, p.precio, p.precio_oferta, p.stock,
           p.imagen, p.destacado, p.activo, p.created_at,
           c.nombre AS categoria_nombre, c.id AS categoria_id,
           m.nombre AS marca_nombre, m.id AS marca_id
    FROM productos p
    LEFT JOIN categorias c ON p.categoria_id = c.id
    LEFT JOIN marcas m ON p.marca_id = m.id
    WHERE p.id = p_id AND p.activo = 1;
END //

CREATE PROCEDURE sp_productos_by_categoria(IN p_categoria_id INT)
BEGIN
    SELECT p.id, p.nombre, p.descripcion, p.precio, p.precio_oferta, p.stock,
           p.imagen, p.destacado, p.created_at,
           c.nombre AS categoria_nombre, c.id AS categoria_id,
           m.nombre AS marca_nombre, m.id AS marca_id
    FROM productos p
    LEFT JOIN categorias c ON p.categoria_id = c.id
    LEFT JOIN marcas m ON p.marca_id = m.id
    WHERE p.categoria_id = p_categoria_id AND p.activo = 1
    ORDER BY p.created_at DESC;
END //

CREATE PROCEDURE sp_productos_destacados()
BEGIN
    SELECT p.id, p.nombre, p.descripcion, p.precio, p.precio_oferta, p.stock,
           p.imagen, p.destacado, p.created_at,
           c.nombre AS categoria_nombre, c.id AS categoria_id,
           m.nombre AS marca_nombre, m.id AS marca_id
    FROM productos p
    LEFT JOIN categorias c ON p.categoria_id = c.id
    LEFT JOIN marcas m ON p.marca_id = m.id
    WHERE p.destacado = 1 AND p.activo = 1
    ORDER BY p.created_at DESC
    LIMIT 8;
END //

CREATE PROCEDURE sp_productos_buscar(IN p_query VARCHAR(200))
BEGIN
    SELECT p.id, p.nombre, p.descripcion, p.precio, p.precio_oferta, p.stock,
           p.imagen, p.destacado, p.created_at,
           c.nombre AS categoria_nombre, c.id AS categoria_id,
           m.nombre AS marca_nombre, m.id AS marca_id
    FROM productos p
    LEFT JOIN categorias c ON p.categoria_id = c.id
    LEFT JOIN marcas m ON p.marca_id = m.id
    WHERE p.activo = 1 AND (
        p.nombre LIKE CONCAT('%', p_query, '%') OR
        p.descripcion LIKE CONCAT('%', p_query, '%') OR
        c.nombre LIKE CONCAT('%', p_query, '%') OR
        m.nombre LIKE CONCAT('%', p_query, '%')
    )
    ORDER BY p.nombre;
END //

CREATE PROCEDURE sp_productos_admin_all()
BEGIN
    SELECT p.id, p.nombre, p.descripcion, p.precio, p.precio_oferta, p.stock,
           p.imagen, p.destacado, p.activo, p.created_at,
           c.nombre AS categoria_nombre, c.id AS categoria_id,
           m.nombre AS marca_nombre, m.id AS marca_id
    FROM productos p
    LEFT JOIN categorias c ON p.categoria_id = c.id
    LEFT JOIN marcas m ON p.marca_id = m.id
    ORDER BY p.id DESC;
END //

CREATE PROCEDURE sp_producto_create(
    IN p_nombre VARCHAR(200),
    IN p_descripcion TEXT,
    IN p_precio DECIMAL(10,2),
    IN p_precio_oferta DECIMAL(10,2),
    IN p_stock INT,
    IN p_imagen VARCHAR(255),
    IN p_categoria_id INT,
    IN p_marca_id INT,
    IN p_destacado TINYINT
)
BEGIN
    INSERT INTO productos (nombre, descripcion, precio, precio_oferta, stock, imagen, categoria_id, marca_id, destacado)
    VALUES (p_nombre, p_descripcion, p_precio, p_precio_oferta, p_stock, p_imagen, p_categoria_id, p_marca_id, p_destacado);
    SELECT LAST_INSERT_ID() AS id;
END //

CREATE PROCEDURE sp_producto_update(
    IN p_id INT,
    IN p_nombre VARCHAR(200),
    IN p_descripcion TEXT,
    IN p_precio DECIMAL(10,2),
    IN p_precio_oferta DECIMAL(10,2),
    IN p_stock INT,
    IN p_imagen VARCHAR(255),
    IN p_categoria_id INT,
    IN p_marca_id INT,
    IN p_destacado TINYINT
)
BEGIN
    UPDATE productos SET
        nombre = p_nombre,
        descripcion = p_descripcion,
        precio = p_precio,
        precio_oferta = p_precio_oferta,
        stock = p_stock,
        imagen = COALESCE(p_imagen, imagen),
        categoria_id = p_categoria_id,
        marca_id = p_marca_id,
        destacado = p_destacado
    WHERE id = p_id;
END //

CREATE PROCEDURE sp_producto_delete(IN p_id INT)
BEGIN
    UPDATE productos SET activo = 0 WHERE id = p_id;
END //

CREATE PROCEDURE sp_categorias_get_all()
BEGIN
    SELECT * FROM categorias WHERE activo = 1 ORDER BY nombre;
END //

CREATE PROCEDURE sp_categoria_create(IN p_nombre VARCHAR(100), IN p_descripcion TEXT, IN p_icono VARCHAR(50))
BEGIN
    INSERT INTO categorias (nombre, descripcion, icono) VALUES (p_nombre, p_descripcion, p_icono);
    SELECT LAST_INSERT_ID() AS id;
END //

CREATE PROCEDURE sp_categoria_update(IN p_id INT, IN p_nombre VARCHAR(100), IN p_descripcion TEXT, IN p_icono VARCHAR(50))
BEGIN
    UPDATE categorias SET nombre = p_nombre, descripcion = p_descripcion, icono = p_icono WHERE id = p_id;
END //

CREATE PROCEDURE sp_categoria_delete(IN p_id INT)
BEGIN
    UPDATE categorias SET activo = 0 WHERE id = p_id;
END //

CREATE PROCEDURE sp_marcas_get_all()
BEGIN
    SELECT * FROM marcas WHERE activo = 1 ORDER BY nombre;
END //

CREATE PROCEDURE sp_marca_create(IN p_nombre VARCHAR(100))
BEGIN
    INSERT INTO marcas (nombre) VALUES (p_nombre);
    SELECT LAST_INSERT_ID() AS id;
END //

CREATE PROCEDURE sp_marca_update(IN p_id INT, IN p_nombre VARCHAR(100))
BEGIN
    UPDATE marcas SET nombre = p_nombre WHERE id = p_id;
END //

CREATE PROCEDURE sp_marca_delete(IN p_id INT)
BEGIN
    UPDATE marcas SET activo = 0 WHERE id = p_id;
END //

CREATE PROCEDURE sp_usuario_crear(
    IN p_nombre VARCHAR(100),
    IN p_email VARCHAR(100),
    IN p_password VARCHAR(255),
    IN p_telefono VARCHAR(20),
    OUT p_id INT,
    OUT p_error VARCHAR(100)
)
BEGIN
    DECLARE email_exists INT DEFAULT 0;
    SELECT COUNT(*) INTO email_exists FROM usuarios WHERE email = p_email;
    IF email_exists > 0 THEN
        SET p_id = 0;
        SET p_error = 'El email ya está registrado';
    ELSE
        INSERT INTO usuarios (nombre, email, password, telefono) VALUES (p_nombre, p_email, p_password, p_telefono);
        SET p_id = LAST_INSERT_ID();
        SET p_error = '';
    END IF;
END //

CREATE PROCEDURE sp_usuario_login(IN p_email VARCHAR(100))
BEGIN
    SELECT id, nombre, email, password, rol, activo FROM usuarios WHERE email = p_email AND activo = 1;
END //

CREATE PROCEDURE sp_usuario_get_by_id(IN p_id INT)
BEGIN
    SELECT id, nombre, email, telefono, direccion, rol FROM usuarios WHERE id = p_id;
END //

CREATE PROCEDURE sp_pedido_create(
    IN p_usuario_id INT,
    IN p_total DECIMAL(10,2),
    IN p_nombre_cliente VARCHAR(100),
    IN p_email_cliente VARCHAR(100),
    IN p_telefono VARCHAR(20),
    IN p_direccion TEXT,
    IN p_notas TEXT,
    OUT p_pedido_id INT
)
BEGIN
    INSERT INTO pedidos (usuario_id, total, nombre_cliente, email_cliente, telefono, direccion, notas)
    VALUES (p_usuario_id, p_total, p_nombre_cliente, p_email_cliente, p_telefono, p_direccion, p_notas);
    SET p_pedido_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE sp_pedido_detalle_add(
    IN p_pedido_id INT,
    IN p_producto_id INT,
    IN p_cantidad INT,
    IN p_precio_unitario DECIMAL(10,2)
)
BEGIN
    INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio_unitario)
    VALUES (p_pedido_id, p_producto_id, p_cantidad, p_precio_unitario);
    UPDATE productos SET stock = stock - p_cantidad WHERE id = p_producto_id;
END //

CREATE PROCEDURE sp_pedidos_get_all()
BEGIN
    SELECT p.*, u.nombre AS usuario_nombre
    FROM pedidos p
    LEFT JOIN usuarios u ON p.usuario_id = u.id
    ORDER BY p.created_at DESC;
END //

CREATE PROCEDURE sp_pedidos_by_usuario(IN p_usuario_id INT)
BEGIN
    SELECT * FROM pedidos WHERE usuario_id = p_usuario_id ORDER BY created_at DESC;
END //

CREATE PROCEDURE sp_pedido_get_detalle(IN p_pedido_id INT)
BEGIN
    SELECT dp.*, prod.nombre AS producto_nombre, prod.imagen AS producto_imagen
    FROM detalle_pedidos dp
    JOIN productos prod ON dp.producto_id = prod.id
    WHERE dp.pedido_id = p_pedido_id;
END //

CREATE PROCEDURE sp_pedido_update_estado(IN p_pedido_id INT, IN p_estado VARCHAR(20))
BEGIN
    UPDATE pedidos SET estado = p_estado WHERE id = p_pedido_id;
END //

CREATE PROCEDURE sp_admin_stats()
BEGIN
    SELECT
        (SELECT COUNT(*) FROM productos WHERE activo = 1) AS total_productos,
        (SELECT COUNT(*) FROM pedidos) AS total_pedidos,
        (SELECT COALESCE(SUM(total), 0) FROM pedidos WHERE estado != 'cancelado') AS total_ventas,
        (SELECT COUNT(*) FROM usuarios WHERE rol = 'cliente') AS total_clientes;
END //

DELIMITER ;
