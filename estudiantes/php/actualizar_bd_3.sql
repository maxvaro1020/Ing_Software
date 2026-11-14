USE sistema_estudiantes;
-- Contenido extendido de cada curso (lema, qué aprenderás, para quién es, foto)
ALTER TABLE cursos ADD COLUMN IF NOT EXISTS lema VARCHAR(255) NULL;
ALTER TABLE cursos ADD COLUMN IF NOT EXISTS aprenderas TEXT NULL;
ALTER TABLE cursos ADD COLUMN IF NOT EXISTS para_quien VARCHAR(255) NULL;
ALTER TABLE cursos ADD COLUMN IF NOT EXISTS foto_url VARCHAR(500) NULL;
UPDATE cursos SET lema='Para viajar, trabajar y compartir tus ideas sin fronteras.', aprenderas='Presentarte y mantener conversaciones cotidianas
Entender y responder en llamadas, viajes y trámites
Escribir correos y mensajes claros
Mejorar pronunciación y comprensión auditiva
Prepararte para certificaciones (opcional)', para_quien='Jóvenes y adultos, desde quienes empiezan de cero hasta quienes quieren perfeccionar su inglés profesional.', foto_url='https://images.unsplash.com/photo-1687862802690-10c1f32d7b1f?auto=format&fit=crop&w=1800&q=75', descripcion='El inglés es la puerta más amplia para estudiar, trabajar y viajar. En nuestro curso practicas desde la primera clase con situaciones reales: presentarte, pedir información, participar en una reunión o escribir un correo. Los grupos son pequeños para que hables mucho y recibas corrección personal.' WHERE nombre LIKE 'Ingl%' AND (lema IS NULL OR lema='');
UPDATE cursos SET lema='Conecta con nuevas culturas, una conversación a la vez.', aprenderas='Saludar, pedir y moverte en situaciones cotidianas
Pronunciar con confianza los sonidos del francés
Entender textos y audios sencillos
Conocer cultura y costumbres francófonas
Preparar niveles base de certificación (opcional)', para_quien='Cualquier persona con curiosidad por el idioma; no necesitas conocimientos previos para empezar.', foto_url='https://images.unsplash.com/photo-1749867817713-4dc76f47dca4?auto=format&fit=crop&w=1800&q=75', descripcion='Aprende francés de forma natural y divertida: pronunciación, expresiones del día a día y cultura de los países francófonos. Ideal para viajar, estudiar o simplemente disfrutar de un idioma lleno de matices. Cada clase combina conversación, escucha y juegos de rol.' WHERE nombre LIKE 'Fran%' AND (lema IS NULL OR lema='');
UPDATE cursos SET lema='Descubre un idioma lleno de vida, arte y encuentros.', aprenderas='Conversar en restaurantes, viajes y encuentros sociales
Entender el ritmo y la entonación italianos
Leer textos breves y comprender canciones y películas
Usar los verbos más frecuentes con seguridad
Descubrir expresiones y cultura de Italia', para_quien='Principiantes y estudiantes con base de otros idiomas romances que quieren avanzar rápido.', foto_url='https://images.unsplash.com/photo-1761824615173-5f3c622bf7b7?auto=format&fit=crop&w=1800&q=75', descripcion='El italiano se aprende rápido y se disfruta aún más. Trabajamos conversación, música, cine y gastronomía para que avances con motivación. Perfecto para viajeros, amantes del arte o quienes tienen raíces italianas.' WHERE nombre LIKE 'Ital%' AND (lema IS NULL OR lema='');
UPDATE cursos SET lema='Abre nuevas puertas en tu vida personal y profesional.', aprenderas='Comunicarte en situaciones de estudio, trabajo y viaje
Entender la estructura de la oración alemana
Ampliar vocabulario profesional y cotidiano
Redactar textos y correos formales
Prepararte para exámenes de nivel (opcional)', para_quien='Estudiantes y profesionales que buscan becas, intercambio o empleo en países de habla alemana.', foto_url='https://images.unsplash.com/photo-1780049921469-4afa2c328d4e?auto=format&fit=crop&w=1800&q=75', descripcion='El alemán abre oportunidades académicas y laborales en Europa. Con una metodología clara y progresiva, dominas la estructura del idioma sin perder la conversación: gramática explicada de forma sencilla, práctica constante y vocabulario útil desde el inicio.' WHERE nombre LIKE 'Alem%' AND (lema IS NULL OR lema='');
