-- Script de migración para agregar columna 'activo' a la tabla alumnos
-- Ejecutar este script si ya tienes la base de datos creada

USE sistema_alumnos;

-- Agregar columna activo si no existe
ALTER TABLE alumnos 
ADD COLUMN IF NOT EXISTS activo TINYINT(1) DEFAULT 1 AFTER grupo_id;

-- Actualizar todos los alumnos existentes a activo
UPDATE alumnos SET activo = 1 WHERE activo IS NULL;

-- Confirmar cambios
SELECT 'Migración completada exitosamente. Columna activo agregada.' as mensaje;
