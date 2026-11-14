USE sistema_estudiantes;
-- Ejecutar DESPUÉS de schema.sql e instalar_grupos_listas.sql (compatible con MariaDB de XAMPP)
ALTER TABLE grupos ADD COLUMN IF NOT EXISTS cupo INT NOT NULL DEFAULT 15;
ALTER TABLE inscripciones ADD COLUMN IF NOT EXISTS id_grupo INT NULL;
ALTER TABLE inscripciones ADD CONSTRAINT fk_insc_grupo FOREIGN KEY (id_grupo) REFERENCES grupos(id_grupo) ON DELETE SET NULL;
