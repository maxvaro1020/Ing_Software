-- ============================================================================
-- ESQUEMA UNIFICADO DE BASE DE DATOS: SISTEMA DE GESTIÓN "LANGUAGE SCHOOL"
-- Consolidación de schema base + instalar_grupos_listas + actualizar_bd (1, 2, 3) + migrar.php
-- Compatible con MySQL 8.0+ / MariaDB (XAMPP)
-- ============================================================================

CREATE DATABASE IF NOT EXISTS sistema_estudiantes
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE sistema_estudiantes;

-- ----------------------------------------------------------------------------
-- 1. Usuarios / Alumnos
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(30) NULL,
    fecha_nacimiento DATE NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ----------------------------------------------------------------------------
-- 2. Cursos (incluye columnas extendidas de presentación del catálogo)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS cursos (
    id_curso INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT NULL,
    duracion_horas INT NULL,
    lema VARCHAR(255) NULL,
    aprenderas TEXT NULL,
    para_quien VARCHAR(255) NULL,
    foto_url VARCHAR(500) NULL,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo'
) ENGINE=InnoDB;

-- ----------------------------------------------------------------------------
-- 3. Niveles Académicos
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS niveles (
    id_nivel INT AUTO_INCREMENT PRIMARY KEY,
    nombre_nivel VARCHAR(50) NOT NULL,
    descripcion TEXT NULL
) ENGINE=InnoDB;

-- ----------------------------------------------------------------------------
-- 4. Expedientes Académicos (Relación 1:1 con Usuarios)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS expedientes (
    id_expediente INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL UNIQUE,
    id_nivel INT NULL,
    observaciones TEXT NULL,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_exp_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    CONSTRAINT fk_exp_nivel FOREIGN KEY (id_nivel) REFERENCES niveles(id_nivel) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ----------------------------------------------------------------------------
-- 5. Grupos (con cupo máximo y vínculo a Cursos)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS grupos (
    id_grupo INT AUTO_INCREMENT PRIMARY KEY,
    id_curso INT NULL,
    nombre_grupo VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255) NULL,
    horario VARCHAR(100) NULL,
    modalidad ENUM('presencial', 'en_linea', 'mixta') DEFAULT 'presencial',
    cupo INT NOT NULL DEFAULT 15,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_grupo_curso FOREIGN KEY (id_curso) REFERENCES cursos(id_curso) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ----------------------------------------------------------------------------
-- 6. Relación Grupo - Alumnos
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS grupo_alumnos (
    id_grupo INT NOT NULL,
    id_usuario INT NOT NULL,
    fecha_asignacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_grupo, id_usuario),
    CONSTRAINT fk_ga_grupo FOREIGN KEY (id_grupo) REFERENCES grupos(id_grupo) ON DELETE CASCADE,
    CONSTRAINT fk_ga_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------------------------------------------------
-- 7. Listas de Alumnos (Generador de listas / listas de espera)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS listas_alumnos (
    id_lista INT AUTO_INCREMENT PRIMARY KEY,
    nombre_lista VARCHAR(120) NOT NULL,
    descripcion VARCHAR(255) NULL,
    id_grupo INT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_lista_grupo FOREIGN KEY (id_grupo) REFERENCES grupos(id_grupo) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ----------------------------------------------------------------------------
-- 8. Detalle de Listas de Alumnos
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS lista_alumnos_detalle (
    id_lista INT NOT NULL,
    id_usuario INT NOT NULL,
    fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_lista, id_usuario),
    CONSTRAINT fk_lad_lista FOREIGN KEY (id_lista) REFERENCES listas_alumnos(id_lista) ON DELETE CASCADE,
    CONSTRAINT fk_lad_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------------------------------------------------
-- 9. Inscripciones (vinculada a Usuario, Curso y Grupo)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS inscripciones (
    id_inscripcion INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_curso INT NOT NULL,
    id_grupo INT NULL,
    fecha_inscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('pendiente', 'confirmada', 'cancelada') DEFAULT 'pendiente',
    CONSTRAINT fk_insc_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    CONSTRAINT fk_insc_curso FOREIGN KEY (id_curso) REFERENCES cursos(id_curso) ON DELETE CASCADE,
    CONSTRAINT fk_insc_grupo FOREIGN KEY (id_grupo) REFERENCES grupos(id_grupo) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ----------------------------------------------------------------------------
-- 10. Pagos (incluye campos de validación de Servicios Financieros)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS pagos (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_inscripcion INT NOT NULL,
    monto DECIMAL(10, 2) NOT NULL,
    comprobante_url VARCHAR(255) NULL,
    estado_pago ENUM('pendiente', 'aprobado', 'rechazado') DEFAULT 'pendiente',
    observaciones VARCHAR(500) NULL,
    validado_por VARCHAR(120) NULL,
    fecha_pago TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_validacion DATETIME NULL,
    CONSTRAINT fk_pago_inscripcion FOREIGN KEY (id_inscripcion) REFERENCES inscripciones(id_inscripcion) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------------------------------------------------
-- 11. Preguntas Frecuentes (FAQs)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS faqs (
    id_faq INT AUTO_INCREMENT PRIMARY KEY,
    pregunta TEXT NOT NULL,
    respuesta TEXT NOT NULL,
    categoria VARCHAR(50) NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ----------------------------------------------------------------------------
-- 12. Trazabilidad / Auditoría
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS trazabilidad (
    id_trazabilidad INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NULL,
    accion VARCHAR(100) NOT NULL,
    descripcion TEXT NULL,
    fecha_evento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_traz_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================================
-- DATOS INICIALES (Catálogo base de Cursos, Niveles y FAQs - Idempotente)
-- ============================================================================

INSERT INTO cursos (nombre, descripcion, duracion_horas, lema, aprenderas, para_quien, foto_url)
SELECT * FROM (
    SELECT 
        'Inglés' AS nombre,
        'El inglés es la puerta más amplia para estudiar, trabajar y viajar. En nuestro curso practicas desde la primera clase con situaciones reales: presentarte, pedir información, participar en una reunión o escribir un correo. Los grupos son pequeños para que hables mucho y recibas corrección personal.' AS descripcion,
        48 AS duracion_horas,
        'Para viajar, trabajar y compartir tus ideas sin fronteras.' AS lema,
        'Presentarte y mantener conversaciones cotidianas
Entender y responder en llamadas, viajes y trámites
Escribir correos y mensajes claros
Mejorar pronunciación y comprensión auditiva
Prepararte para certificaciones (opcional)' AS aprenderas,
        'Jóvenes y adultos, desde quienes empiezan de cero hasta quienes quieren perfeccionar su inglés profesional.' AS para_quien,
        'https://images.unsplash.com/photo-1687862802690-10c1f32d7b1f?auto=format&fit=crop&w=1800&q=75' AS foto_url
    UNION ALL
    SELECT 
        'Francés',
        'Aprende francés de forma natural y divertida: pronunciación, expresiones del día a día y cultura de los países francófonos. Ideal para viajar, estudiar o simplemente disfrutar de un idioma lleno de matices. Cada clase combina conversación, escucha y juegos de rol.',
        48,
        'Conecta con nuevas culturas, una conversación a la vez.',
        'Saludar, pedir y moverte en situaciones cotidianas
Pronunciar con confianza los sonidos del francés
Entender textos y audios sencillos
Conocer cultura y costumbres francófonas
Preparar niveles base de certificación (opcional)',
        'Cualquier persona con curiosidad por el idioma; no necesitas conocimientos previos para empezar.',
        'https://images.unsplash.com/photo-1749867817713-4dc76f47dca4?auto=format&fit=crop&w=1800&q=75'
    UNION ALL
    SELECT 
        'Italiano',
        'El italiano se aprende rápido y se disfruta aún más. Trabajamos conversación, música, cine y gastronomía para que avances con motivación. Perfecto para viajeros, amantes del arte o quienes tienen raíces italianas.',
        48,
        'Descubre un idioma lleno de vida, arte y encuentros.',
        'Conversar en restaurantes, viajes y encuentros sociales
Entender el ritmo y la entonación italianos
Leer textos breves y comprender canciones y películas
Usar los verbos más frecuentes con seguridad
Descubrir expresiones y cultura de Italia',
        'Principiantes y estudiantes con base de otros idiomas romances que quieren avanzar rápido.',
        'https://images.unsplash.com/photo-1761824615173-5f3c622bf7b7?auto=format&fit=crop&w=1800&q=75'
    UNION ALL
    SELECT 
        'Alemán',
        'El alemán abre oportunidades académicas y laborales en Europa. Con una metodología clara y progresiva, dominas la estructura del idioma sin perder la conversación: gramática explicada de forma sencilla, práctica constante y vocabulario útil desde el inicio.',
        48,
        'Abre nuevas puertas en tu vida personal y profesional.',
        'Comunicarte en situaciones de estudio, trabajo y viaje
Entender la estructura de la oración alemana
Ampliar vocabulario profesional y cotidiano
Redactar textos y correos formales
Prepararte para exámenes de nivel (opcional)',
        'Estudiantes y profesionales que buscan becas, intercambio o empleo en países de habla alemana.',
        'https://images.unsplash.com/photo-1780049921469-4afa2c328d4e?auto=format&fit=crop&w=1800&q=75'
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM cursos);

INSERT INTO niveles (nombre_nivel, descripcion)
SELECT * FROM (
    SELECT 'Básico' AS nombre_nivel, 'Primeras conversaciones, vocabulario esencial y estructuras sencillas.' AS descripcion
    UNION ALL
    SELECT 'Intermedio', 'Expresar opiniones, contar experiencias y desenvolverte en trabajo o viaje.'
    UNION ALL
    SELECT 'Avanzado', 'Fluidez, argumentación y precisión para entornos profesionales.'
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM niveles);

INSERT INTO faqs (pregunta, respuesta, categoria)
SELECT * FROM (
    SELECT '¿Puedo empezar si no sé nada del idioma?' AS pregunta, 'Claro. Tenemos cursos de iniciación para empezar desde cero. No necesitas conocimientos previos.' AS respuesta, 'general' AS categoria
    UNION ALL
    SELECT '¿Cómo sé qué nivel me corresponde?', 'Antes de elegir un curso podemos orientarte con una valoración de nivel para encontrar el grupo adecuado.', 'general'
    UNION ALL
    SELECT '¿Las clases son online o presenciales?', 'Puedes elegir entre clases presenciales y online en directo. Consulta las modalidades y horarios de cada grupo.', 'general'
    UNION ALL
    SELECT '¿Cómo puedo inscribirme en un curso?', 'Crea tu cuenta desde «Mi cuenta», elige un idioma y pulsa «Inscribirme». Servicios Escolares confirmará tu lugar.', 'general'
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM faqs);
