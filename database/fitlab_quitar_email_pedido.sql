-- Script opcional para quitar columnas antiguas de email de pedido.
-- Ejecutar solo si ya las habías importado antes.
ALTER TABLE pedido DROP COLUMN IF EXISTS email_enviado;
ALTER TABLE pedido DROP COLUMN IF EXISTS email_error;
