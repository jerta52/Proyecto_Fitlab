-- Migración opcional para bases existentes de FitLab.
-- Si se importa database/fitlab.sql desde cero, no hace falta ejecutar este archivo.

CREATE TABLE IF NOT EXISTS servicios (
    id_servicio INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    imagen VARCHAR(255) NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS imagenes_instalaciones (
    id_imagen INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255),
    ruta VARCHAR(255) NOT NULL,
    activa TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS informacion_gimnasio (
    id_info INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT NOT NULL,
    direccion VARCHAR(255),
    telefono VARCHAR(20),
    email VARCHAR(150),
    mapa_url TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE informacion_gimnasio ADD COLUMN hero_titulo TEXT NULL;
ALTER TABLE informacion_gimnasio ADD COLUMN hero_subtitulo TEXT NULL;

CREATE TABLE IF NOT EXISTS horario_gimnasio (
    id_horario INT AUTO_INCREMENT PRIMARY KEY,
    dia VARCHAR(50) NOT NULL,
    hora_apertura TIME NOT NULL,
    hora_cierre TIME NOT NULL,
    cerrado TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS configuracion_apariencia;
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

INSERT INTO configuracion_apariencia (codigo, nombre, descripcion, color_principal, color_secundario, color_fondo, fuente_principal, fuente_titulos, tamano_fuente_base, activo) VALUES
('default', 'Diseño default', 'Diseño azul original de FitLab.', '#78B7FF', '#0077FF', '#101112', 'Lato', 'Bebas Neue', 16, 1),
('contraste', 'Diseño contraste', 'Diseño con mayor contraste y fuente Montserrat.', '#22C55E', '#16A34A', '#101112', 'Montserrat', 'Oswald', 17, 0),
('energia', 'Diseño energía', 'Diseño cálido con tono naranja y fuente Roboto.', '#F59E0B', '#EF4444', '#101112', 'Roboto', 'Oswald', 18, 0);
