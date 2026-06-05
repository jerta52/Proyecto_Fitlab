-- Corrección para evitar error 500 al procesar pago si la tabla pedido está antigua.
-- Importar en phpMyAdmin dentro de la base de datos de InfinityFree.

ALTER TABLE pedido ADD COLUMN IF NOT EXISTS metodo_pago VARCHAR(50) DEFAULT 'Tarjeta' AFTER estado;
