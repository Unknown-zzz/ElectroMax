USE electromax;

INSERT INTO categorias (nombre, descripcion, icono) VALUES
('Televisores',         'Smart TV, LED, 4K, OLED y más',            'bi-tv'),
('Refrigeradoras',      'Refrigeradoras, congeladoras y frigobar',   'bi-thermometer-snow'),
('Lavadoras',           'Lavadoras automáticas y secadoras',         'bi-droplet-fill'),
('Computación',         'Laptops, PCs de escritorio y accesorios',   'bi-laptop'),
('Celulares',           'Smartphones y tablets',                     'bi-phone'),
('Audio y Video',       'Parlantes, audífonos y equipos de sonido',  'bi-speaker'),
('Cocina',              'Microondas, licuadoras, freidoras y más',   'bi-cup-hot'),
('Aires Acondicionados','Split inverter, portátiles y ventiladores', 'bi-wind');

INSERT INTO marcas (nombre) VALUES
('Samsung'),('LG'),('Sony'),('Hisense'),('Xiaomi'),
('Apple'),('Whirlpool'),('Mabe'),('Electrolux'),('Panasonic');

-- Contraseña: admin123
INSERT INTO usuarios (nombre, email, password, rol) VALUES
('Administrador', 'admin@electromax.com',
 '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B99tAHm', 'admin');

INSERT INTO productos (nombre, descripcion, precio, precio_oferta, stock, imagen, categoria_id, marca_id, destacado) VALUES
('Smart TV 55" 4K UHD Samsung',
 'Televisor Samsung Crystal UHD 55 pulgadas 4K, procesador Crystal 4K, acceso a Netflix, YouTube, Prime Video y más aplicaciones. Diseño sin bisel con pantalla inmersiva.',
 3500.00, 2999.00, 10, NULL, 1, 1, 1),

('Smart TV 65" OLED LG',
 'Televisor LG OLED 65 pulgadas 4K, el mejor contraste del mercado, colores perfectos incluso con luz ambiente. Compatible con Dolby Vision y Dolby Atmos.',
 6800.00, NULL, 5, NULL, 1, 2, 1),

('Smart TV 43" Hisense FHD',
 'Televisor Hisense 43 pulgadas Full HD, VIDAA Smart TV con acceso a contenido de streaming. Ideal para dormitorios y cocinas.',
 1200.00, 999.00, 15, NULL, 1, 4, 0),

('Refrigeradora No Frost 350L Samsung',
 'Refrigeradora Samsung No Frost 350 litros, sistema Twin Cooling Plus para mantener la humedad correcta en cada compartimento. Eficiencia energética A+.',
 2800.00, 2499.00, 8, NULL, 2, 1, 1),

('Refrigeradora Bottom Freezer LG 310L',
 'Refrigeradora LG 310 litros con congelador en la parte inferior, tecnología Linear Compressor para mayor eficiencia y menor ruido. Garantía 10 años en compresor.',
 2300.00, NULL, 6, NULL, 2, 2, 0),

('Frigobar Mabe 90L',
 'Frigobar Mabe 90 litros, perfecto para habitación u oficina. Silencioso, eficiente y con compartimento para congelar.',
 550.00, 480.00, 20, NULL, 2, 8, 0),

('Lavadora Automática LG 15kg',
 'Lavadora LG 15kg carga frontal con motor inverter directo. Tecnología AI DD detecta el tipo de tela y ajusta el lavado automáticamente. Certificación ENERGY STAR.',
 1800.00, NULL, 12, NULL, 3, 2, 1),

('Lavadora Whirlpool 12kg Carga Superior',
 'Lavadora Whirlpool 12kg de carga superior con agitador, 12 ciclos de lavado y tecnología 6th Sense para ahorrar agua y energía.',
 1100.00, 950.00, 9, NULL, 3, 7, 0),

('Laptop HP Victus 15" Gaming',
 'Laptop HP Victus 15 pulgadas, AMD Ryzen 5 5600H, 8GB RAM DDR4, 512GB SSD NVMe, GPU NVIDIA GTX 1650 4GB. Pantalla FHD 144Hz ideal para gaming y trabajo.',
 4500.00, 3999.00, 7, NULL, 4, NULL, 1),

('Laptop ASUS VivoBook 14"',
 'Laptop ASUS VivoBook 14 pulgadas, Intel Core i5-1135G7, 8GB RAM, 512GB SSD, pantalla NanoEdge IPS. Ultradelgada y liviana para llevar a todos lados.',
 3200.00, NULL, 11, NULL, 4, NULL, 0),

('iPhone 15 Pro 256GB',
 'Apple iPhone 15 Pro 256GB, chip A17 Pro, sistema de cámara Pro con teleobjetivo 5x, titanio de grado aeroespacial. USB-C con velocidades USB 3.',
 9500.00, NULL, 4, NULL, 5, 6, 1),

('Samsung Galaxy S24 128GB',
 'Samsung Galaxy S24 128GB, Snapdragon 8 Gen 3, pantalla Dynamic AMOLED 2X 6.2" 120Hz, cámara principal 50MP. Incluye Galaxy AI para edición y traducción inteligente.',
 4800.00, 4299.00, 8, NULL, 5, 1, 1),

('Xiaomi Redmi Note 13 Pro 256GB',
 'Xiaomi Redmi Note 13 Pro 256GB, cámara 200MP con OIS, pantalla AMOLED 1.5K 120Hz, carga rápida 67W. La mejor relación precio-calidad del mercado.',
 1500.00, 1299.00, 25, NULL, 5, 5, 0),

('Audífonos Sony WH-1000XM5',
 'Audífonos Sony WH-1000XM5 con cancelación de ruido líder en la industria, 30 horas de batería, Bluetooth 5.2 y carga rápida (10 min = 3 horas).',
 1200.00, 999.00, 18, NULL, 6, 3, 1),

('Parlante JBL Charge 5',
 'Parlante portátil JBL Charge 5, resistente al agua IP67, 20 horas de reproducción, función powerbank para cargar tu teléfono. Sonido potente 360°.',
 680.00, NULL, 30, NULL, 6, NULL, 0),

('Microondas Samsung 28L con Grill',
 'Microondas Samsung 28 litros con función grill, pantalla digital, 900W de potencia, 10 niveles de potencia y descongelación automática.',
 650.00, 580.00, 22, NULL, 7, 1, 0),

('Freidora de Aire Philips 4.1L',
 'Freidora de aire Philips Airfryer XL 4.1 litros, tecnología Rapid Air para cocinar con hasta 90% menos grasa. Incluye libro de recetas.',
 850.00, 750.00, 16, NULL, 7, NULL, 1),

('Aire Acondicionado LG Inverter 18000 BTU',
 'Aire acondicionado split LG 18000 BTU con inverter, control WiFi desde la app ThinQ, modo dual para frío y calor, filtro antibacterias PM1.0.',
 3200.00, NULL, 7, NULL, 8, 2, 1);
