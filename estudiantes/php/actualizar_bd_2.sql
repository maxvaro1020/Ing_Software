USE sistema_estudiantes;
-- Conecta grupos con cursos y agrega datos iniciales (cursos, niveles, preguntas frecuentes)
ALTER TABLE grupos ADD COLUMN IF NOT EXISTS id_curso INT NULL;
ALTER TABLE grupos ADD CONSTRAINT fk_grupo_curso FOREIGN KEY (id_curso) REFERENCES cursos(id_curso) ON DELETE SET NULL;

INSERT INTO cursos(nombre,descripcion,duracion_horas)
SELECT * FROM (
 SELECT 'Inglés' n,'El inglés es la puerta más amplia para estudiar, trabajar y viajar. Practicas desde la primera clase con situaciones reales: presentarte, viajar, trabajar y compartir tus ideas sin fronteras.' d,48 h
 UNION ALL SELECT 'Francés','Aprende francés de forma natural: pronunciación, expresiones del día a día y cultura de los países francófonos. Ideal para viajar, estudiar y conectar con nuevas culturas.',48
 UNION ALL SELECT 'Italiano','Descubre un idioma lleno de vida, arte y encuentros. Conversación, vocabulario cotidiano y cultura italiana en grupos reducidos.',48
 UNION ALL SELECT 'Alemán','Abre nuevas puertas en tu vida personal y profesional con un alemán práctico, claro y orientado a la conversación.',48
) t WHERE NOT EXISTS (SELECT 1 FROM cursos);

INSERT INTO niveles(nombre_nivel,descripcion)
SELECT * FROM (
 SELECT 'Básico' a,'Primeras conversaciones, vocabulario esencial y estructuras sencillas.' b
 UNION ALL SELECT 'Intermedio','Expresar opiniones, contar experiencias y desenvolverte en trabajo o viaje.'
 UNION ALL SELECT 'Avanzado','Fluidez, argumentación y precisión para entornos profesionales.'
) t WHERE NOT EXISTS (SELECT 1 FROM niveles);

INSERT INTO faqs(pregunta,respuesta,categoria)
SELECT * FROM (
 SELECT '¿Puedo empezar si no sé nada del idioma?' p,'Claro. Tenemos cursos de iniciación para empezar desde cero. No necesitas conocimientos previos.' r,'general' c
 UNION ALL SELECT '¿Cómo sé qué nivel me corresponde?','Antes de elegir un curso podemos orientarte con una valoración de nivel para encontrar el grupo adecuado.','general'
 UNION ALL SELECT '¿Las clases son online o presenciales?','Puedes elegir entre clases presenciales y online en directo. Consulta las modalidades y horarios de cada grupo.','general'
 UNION ALL SELECT '¿Cómo puedo inscribirme en un curso?','Crea tu cuenta desde «Mi cuenta», elige un idioma y pulsa «Inscribirme». Servicios Escolares confirmará tu lugar.','general'
) t WHERE NOT EXISTS (SELECT 1 FROM faqs);

-- Si ya tenías grupos por idioma, los enlaza con su curso según el nombre
UPDATE grupos g JOIN cursos c ON g.nombre_grupo LIKE CONCAT(c.nombre,'%') SET g.id_curso=c.id_curso WHERE g.id_curso IS NULL;
