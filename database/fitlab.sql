-- Script completo para crear e inicializar la base de datos de FitLab.


DROP DATABASE IF EXISTS fitlab;
CREATE DATABASE fitlab CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fitlab;

CREATE TABLE rol (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

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
    CONSTRAINT fk_usuario_rol
        FOREIGN KEY (id_rol)
        REFERENCES rol(id_rol)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255)
) ENGINE=InnoDB;

CREATE TABLE producto (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    imagen VARCHAR(255),
    id_categoria INT,
    CONSTRAINT fk_producto_categoria
        FOREIGN KEY (id_categoria)
        REFERENCES categoria(id_categoria)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE carrito (
    id_carrito INT AUTO_INCREMENT PRIMARY KEY,
    fecha_creacion DATETIME NOT NULL,
    id_usuario INT NOT NULL,
    CONSTRAINT fk_carrito_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE detalle_carrito (
    id_detalle_carrito INT AUTO_INCREMENT PRIMARY KEY,
    cantidad INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    id_carrito INT NOT NULL,
    id_producto INT NOT NULL,
    CONSTRAINT fk_detalle_carrito_carrito
        FOREIGN KEY (id_carrito)
        REFERENCES carrito(id_carrito)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_detalle_carrito_producto
        FOREIGN KEY (id_producto)
        REFERENCES producto(id_producto)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE pedido (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    fecha_pedido DATETIME NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    estado VARCHAR(50) NOT NULL,
    id_usuario INT NOT NULL,
    CONSTRAINT fk_pedido_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE detalle_pedido (
    id_detalle_pedido INT AUTO_INCREMENT PRIMARY KEY,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    id_pedido INT NOT NULL,
    id_producto INT NOT NULL,
    CONSTRAINT fk_detalle_pedido_pedido
        FOREIGN KEY (id_pedido)
        REFERENCES pedido(id_pedido)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_detalle_pedido_producto
        FOREIGN KEY (id_producto)
        REFERENCES producto(id_producto)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE calculo_imc (
    id_imc INT AUTO_INCREMENT PRIMARY KEY,
    peso DECIMAL(5,2) NOT NULL,
    altura DECIMAL(5,2) NOT NULL,
    resultado_imc DECIMAL(5,2) NOT NULL,
    fecha DATETIME NOT NULL,
    id_usuario INT NOT NULL,
    CONSTRAINT fk_calculo_imc_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

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
    CONSTRAINT fk_calculo_calorias_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Inserciones iniciales
INSERT INTO rol (id_rol, nombre_rol) VALUES
(1, 'administrador'),
(2, 'usuario');

INSERT INTO categoria (id_categoria, nombre_categoria, descripcion) VALUES
(1, 'Suplementos', 'Productos de suplementación deportiva'),
(2, 'Ropa', 'Ropa deportiva para entrenamiento'),
(3, 'Accesorios', 'Accesorios para el gimnasio y entrenamiento');

INSERT INTO usuario (id_usuario, nombre, apellidos, email, contrasena, telefono, direccion, fecha_registro, id_rol) VALUES
(1, 'Admin', 'FitLab', 'admin@fitlab.com', '$2y$12$YFu3VKzVBGO2hZ/UlKt7GeqFFJWZoOAdL1cE.ovREITXkbhWKHx2W', NULL, NULL, NOW(), 1),
(2, 'Usuario', 'Prueba', 'usuario@fitlab.com', '$2y$12$w2KTQFAXvrxgtUIlM2NmZ.63LWXuR93w3qGkD7GsIlr2XwLrI7E/6', NULL, NULL, NOW(), 2);

INSERT INTO producto (id_producto, nombre_producto, descripcion, precio, stock, imagen, id_categoria) VALUES
(1, 'Proteína Whey', 'Proteína en polvo para recuperación muscular después del entrenamiento.', 29.99, 20, 'whey.png', 1),
(2, 'Camiseta', 'Camiseta cómoda para entrenamientos en el gimnasio.', 39.99, 30, 'camiseta.png', 2),
(3, 'Botella', 'Botella perfecta para entrenar', 9.99, 15, 'botella.png', 3);