-- FitLab - base de datos actualizada para InfinityFree/phpMyAdmin.
-- Importar este archivo dentro de la base de datos creada por el hosting.
-- No contiene CREATE DATABASE ni USE para evitar el error #1044 en InfinityFree.

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS detalle_pedido;
DROP TABLE IF EXISTS pedido;
DROP TABLE IF EXISTS detalle_carrito;
DROP TABLE IF EXISTS carrito;
DROP TABLE IF EXISTS calculo_imc;
DROP TABLE IF EXISTS calculo_calorias;
DROP TABLE IF EXISTS producto;
DROP TABLE IF EXISTS categoria;
DROP TABLE IF EXISTS servicios;
DROP TABLE IF EXISTS imagenes_instalaciones;
DROP TABLE IF EXISTS informacion_gimnasio;
DROP TABLE IF EXISTS horario_gimnasio;
DROP TABLE IF EXISTS configuracion_apariencia;
DROP TABLE IF EXISTS usuario;
DROP TABLE IF EXISTS rol;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE rol (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    direccion VARCHAR(255),
    fecha_registro DATETIME NOT NULL,
    id_rol INT NOT NULL,
    CONSTRAINT fk_usuario_rol FOREIGN KEY (id_rol) REFERENCES rol(id_rol)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE producto (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    imagen VARCHAR(255),
    id_categoria INT,
    CONSTRAINT fk_producto_categoria FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE carrito (
    id_carrito INT AUTO_INCREMENT PRIMARY KEY,
    fecha_creacion DATETIME NOT NULL,
    id_usuario INT NOT NULL,
    CONSTRAINT fk_carrito_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE detalle_carrito (
    id_detalle_carrito INT AUTO_INCREMENT PRIMARY KEY,
    cantidad INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    id_carrito INT NOT NULL,
    id_producto INT NOT NULL,
    UNIQUE KEY uk_carrito_producto (id_carrito, id_producto),
    CONSTRAINT fk_detalle_carrito_carrito FOREIGN KEY (id_carrito) REFERENCES carrito(id_carrito)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_detalle_carrito_producto FOREIGN KEY (id_producto) REFERENCES producto(id_producto)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE pedido (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    fecha_pedido DATETIME NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente','pagado','enviado','entregado','cancelado') NOT NULL DEFAULT 'pendiente',
    metodo_pago VARCHAR(50) DEFAULT 'Tarjeta',
    pdf_generado VARCHAR(255) NULL,
    id_usuario INT NOT NULL,
    CONSTRAINT fk_pedido_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE detalle_pedido (
    id_detalle_pedido INT AUTO_INCREMENT PRIMARY KEY,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    id_pedido INT NOT NULL,
    id_producto INT NOT NULL,
    CONSTRAINT fk_detalle_pedido_pedido FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_detalle_pedido_producto FOREIGN KEY (id_producto) REFERENCES producto(id_producto)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE calculo_imc (
    id_imc INT AUTO_INCREMENT PRIMARY KEY,
    peso DECIMAL(5,2) NOT NULL,
    altura DECIMAL(5,2) NOT NULL,
    resultado_imc DECIMAL(5,2) NOT NULL,
    fecha DATETIME NOT NULL,
    id_usuario INT NOT NULL,
    CONSTRAINT fk_calculo_imc_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE calculo_calorias (
    id_caloria INT AUTO_INCREMENT PRIMARY KEY,
    edad INT NOT NULL,
    sexo VARCHAR(10) NOT NULL,
    peso DECIMAL(5,2) NOT NULL,
    altura DECIMAL(5,2) NOT NULL,
    actividad VARCHAR(50) NOT NULL,
    objetivo VARCHAR(50) NOT NULL,
    calorias_resultado INT NOT NULL,
    fecha DATETIME NOT NULL,
    id_usuario INT NOT NULL,
    CONSTRAINT fk_calculo_calorias_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE servicios (
    id_servicio INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    imagen VARCHAR(255) NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE imagenes_instalaciones (
    id_imagen INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255),
    ruta VARCHAR(255) NOT NULL,
    activa TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE informacion_gimnasio (
    id_info INT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    hero_titulo TEXT NOT NULL,
    hero_subtitulo TEXT NOT NULL,
    descripcion TEXT NOT NULL,
    direccion VARCHAR(255),
    telefono VARCHAR(20),
    email VARCHAR(150),
    mapa_url TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE horario_gimnasio (
    id_horario INT AUTO_INCREMENT PRIMARY KEY,
    dia VARCHAR(50) NOT NULL,
    hora_apertura TIME NOT NULL,
    hora_cierre TIME NOT NULL,
    cerrado TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE configuracion_apariencia (
    id_config INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(30) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255),
    color_principal VARCHAR(20) NOT NULL,
    color_secundario VARCHAR(20) NOT NULL,
    color_fondo VARCHAR(20) NOT NULL DEFAULT '#101112',
    fuente_principal VARCHAR(100) NOT NULL,
    fuente_titulos VARCHAR(100) NOT NULL,
    tamano_fuente_base INT NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO rol (id_rol, nombre_rol) VALUES (1, 'administrador'), (2, 'cliente');

INSERT INTO usuario (id_usuario, nombre, apellidos, email, contrasena, telefono, direccion, fecha_registro, id_rol) VALUES
(1, 'Admin', 'FitLab', 'admin@fitlab.com', '$2y$12$8iyEtV77uk7WUqzRZ9PQheeIMLCO6CQZWXEjg4oh0Df2.HQO3PlHO', '600000001', 'Cuenca', NOW(), 1),
(2, 'Usuario', 'Prueba', 'usuario@fitlab.com', '$2y$12$0c2pilmrfFXXVse5ybWkc.GyRVejSumEnlb5HCZWR7aqVTvODT/ba', '600000002', 'Cuenca', NOW(), 2);

INSERT INTO categoria (id_categoria, nombre_categoria, descripcion) VALUES
(1, 'Suplementos', 'Productos de suplementación deportiva'),
(2, 'Ropa', 'Ropa deportiva para entrenamiento'),
(3, 'Accesorios', 'Accesorios para el gimnasio y entrenamiento');

INSERT INTO producto (id_producto, nombre_producto, descripcion, precio, stock, imagen, id_categoria) VALUES
(1, 'Proteína Whey', 'Proteína en polvo para recuperación muscular después del entrenamiento.', 29.99, 20, 'whey.png', 1),
(2, 'Camiseta', 'Camiseta cómoda para entrenamientos en el gimnasio.', 39.99, 30, 'camiseta.png', 2),
(3, 'Botella', 'Botella perfecta para entrenar.', 9.99, 15, 'botella.png', 3);

INSERT INTO servicios (nombre, descripcion, imagen, activo) VALUES
('Entrenamiento personal', 'Servicio gratuito de orientación inicial y recomendaciones básicas de entrenamiento para nuevos clientes.', 'img/asesor_entrenador.png', 1),
('Nutrición deportiva', 'Consejos generales gratuitos sobre hábitos saludables y alimentación orientada al entrenamiento.', 'img/mancuernas.png', 1),
('Clases dirigidas', 'Información sobre clases colectivas disponibles en el gimnasio, como zumba, funcional o cardio.', 'img/clase_zumba.png', 1),
('Seguimiento personalizado', 'Revisión básica gratuita de la evolución del cliente y orientación sobre objetivos.', 'img/mancuernas.png', 1);

INSERT INTO imagenes_instalaciones (titulo, descripcion, ruta, activa) VALUES
('Zona de musculación', 'Área de entrenamiento de fuerza.', 'img/mancuernas.png', 1),
('Zona de cintas', 'Área de cardio del gimnasio.', 'img/zona-cintas.png', 1),
('Zona de clases', 'Sala de actividades dirigidas.', 'img/zona-clases.png', 1),
('Zona core', 'Espacio de entrenamiento funcional.', 'img/mancuernas.png', 1);

INSERT INTO informacion_gimnasio (id_info, nombre, hero_titulo, hero_subtitulo, descripcion, direccion, telefono, email, mapa_url) VALUES
(1, 'FitLab', 'ENTRENA COMO\nUN ATLETA CON\nLO MEJOR DEL FITNESS', 'Tu gimnasio abierto de alta calidad con servicios gratuitos para orientar tus entrenamientos.', 'Gimnasio local con tienda, servicios personalizados y herramientas de seguimiento.', 'Calle Palancares, 52, 16003 Cuenca', '961 34 23 64', 'fitlab@gmail.com', 'https://maps.google.com/maps?q=cuenca&t=&z=13&ie=UTF8&iwloc=&output=embed');

INSERT INTO horario_gimnasio (dia, hora_apertura, hora_cierre, cerrado) VALUES
('Lunes a Viernes', '06:00:00', '01:00:00', 0),
('Sábados', '08:00:00', '22:00:00', 0),
('Domingos y festivos', '08:00:00', '22:00:00', 0);

INSERT INTO configuracion_apariencia (codigo, nombre, descripcion, color_principal, color_secundario, color_fondo, fuente_principal, fuente_titulos, tamano_fuente_base, activo) VALUES
('default', 'Diseño default', 'Diseño azul original de FitLab.', '#78B7FF', '#0077FF', '#101112', 'Lato', 'Bebas Neue', 16, 1),
('contraste', 'Diseño contraste', 'Diseño con mayor contraste y fuente Montserrat.', '#22C55E', '#16A34A', '#101112', 'Montserrat', 'Oswald', 17, 0),
('energia', 'Diseño energía', 'Diseño cálido con tono naranja y fuente Roboto.', '#F59E0B', '#EF4444', '#101112', 'Roboto', 'Oswald', 18, 0);
