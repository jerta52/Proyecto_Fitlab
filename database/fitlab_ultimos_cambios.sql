-- Migración de últimos cambios FitLab.
-- Importar sobre la base existente si no se quiere reinstalar todo fitlab.sql.


-- Información general: dejar un único registro en id_info = 1.
INSERT INTO informacion_gimnasio (id_info, nombre, hero_titulo, hero_subtitulo, descripcion, direccion, telefono, email, mapa_url)
VALUES (1, 'FitLab', 'ENTRENA COMO
UN ATLETA CON
LO MEJOR DEL FITNESS', 'Tu gimnasio abierto de alta calidad con servicios gratuitos para orientar tus entrenamientos.', 'Gimnasio local con tienda, servicios personalizados y herramientas de seguimiento.', 'Calle Palancares, 52, 16003 Cuenca', '961 34 23 64', 'fitlab@gmail.com', 'https://maps.google.com/maps?q=cuenca&t=&z=13&ie=UTF8&iwloc=&output=embed')
ON DUPLICATE KEY UPDATE id_info = id_info;

DELETE FROM informacion_gimnasio WHERE id_info <> 1;

-- Servicios gratuitos iniciales si no existen.
INSERT INTO servicios (nombre, descripcion, imagen, activo)
SELECT 'Entrenamiento personal', 'Servicio gratuito de orientación inicial y recomendaciones básicas de entrenamiento para nuevos clientes.', 'img/asesor_entrenador.png', 1
WHERE NOT EXISTS (SELECT 1 FROM servicios LIMIT 1);
