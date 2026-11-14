/* ============================================================
   Language Schoolar · Contenido de las páginas de curso
   Las imágenes se llaman por URL (Unsplash). Los textos de horarios son
   ejemplos: cámbialos por los datos reales de GRUPO en tu sistema.
   ============================================================ */
const IMG = "https://images.unsplash.com/photo-";
const Q = "?auto=format&fit=crop&w=1800&q=75";

const CURSOS = {
  ingles: {
    nombre: "Inglés", saludo: "Hello!", foto: IMG + "1687862802690-10c1f32d7b1f" + Q,
    credito: "Benjamin Cheng", creditoUrl: "https://unsplash.com/photos/a-brick-building-with-a-tree-in-front-of-it-PI_hlpBcDXs",
    lema: "Para viajar, trabajar y compartir tus ideas sin fronteras.",
    descripcion: "El inglés es la puerta más amplia para estudiar, trabajar y viajar. En nuestro curso practicas desde la primera clase con situaciones reales: presentarte, pedir información, participar en una reunión o escribir un correo. Los grupos son pequeños para que hables mucho y recibas corrección personal.",
    aprenderas: ["Presentarte y mantener conversaciones cotidianas", "Entender y responder en llamadas, viajes y trámites", "Escribir correos y mensajes claros", "Mejorar pronunciación y comprensión auditiva", "Prepararte para certificaciones (opcional)"],
    paraQuien: "Jóvenes y adultos, desde quienes empiezan de cero hasta quienes quieren perfeccionar su inglés profesional.",
    niveles: [
      ["Básico", "Primeras conversaciones, vocabulario esencial y estructuras sencillas."],
      ["Intermedio", "Expresar opiniones, contar experiencias y desenvolverte en trabajo o viaje."],
      ["Avanzado", "Fluidez, argumentación y precisión para entornos profesionales."]
    ],
    horarios: [
      ["Inglés Básico", "Lun – Mié", "18:00 – 19:30", "Presencial", 8],
      ["Inglés Intermedio", "Mar – Jue", "19:00 – 20:30", "Online en directo", 0],
      ["Inglés Avanzado", "Sáb", "10:00 – 13:00", "Presencial", 5]
    ],
    duracion: "Ciclos de 12 semanas", grupo: "Grupos reducidos"
  },
  frances: {
    nombre: "Francés", saludo: "Bonjour!", foto: IMG + "1749867817713-4dc76f47dca4" + Q,
    credito: "Nathan Cima", creditoUrl: "https://unsplash.com/photos/a-charming-corner-cafe-restaurant-with-a-striped-awning-vG_nEY9j7ow",
    lema: "Conecta con nuevas culturas, una conversación a la vez.",
    descripcion: "Aprende francés de forma natural y divertida: pronunciación, expresiones del día a día y cultura de los países francófonos. Ideal para viajar, estudiar o simplemente disfrutar de un idioma lleno de matices. Cada clase combina conversación, escucha y juegos de rol.",
    aprenderas: ["Saludar, pedir y moverte en situaciones cotidianas", "Pronunciar con confianza los sonidos del francés", "Entender textos y audios sencillos", "Conocer cultura y costumbres francófonas", "Preparar niveles base de certificación (opcional)"],
    paraQuien: "Cualquier persona con curiosidad por el idioma; no necesitas conocimientos previos para empezar.",
    niveles: [
      ["Básico", "Saludos, presentaciones, compras, direcciones y rutina diaria."],
      ["Intermedio", "Narrar, opinar y comprender conversaciones más largas."],
      ["Avanzado", "Conversación fluida, textos complejos y matices culturales."]
    ],
    horarios: [
      ["Francés Básico", "Sáb", "09:00 – 12:00", "Presencial", 4],
      ["Francés Intermedio", "Mar – Jue", "18:00 – 19:30", "Online en directo", 6]
    ],
    duracion: "Ciclos de 12 semanas", grupo: "Máx. 12 personas"
  },
  italiano: {
    nombre: "Italiano", saludo: "Ciao!", foto: IMG + "1761824615173-5f3c622bf7b7" + Q,
    credito: "Hans-Peter Traunig", creditoUrl: "https://unsplash.com/photos/sunlight-streams-down-a-narrow-cobblestone-alleyway-sprsqnyH8EY",
    lema: "Descubre un idioma lleno de vida, arte y encuentros.",
    descripcion: "El italiano se aprende rápido y se disfruta aún más. Trabajamos conversación, música, cine y gastronomía para que avances con motivación. Perfecto para viajeros, amantes del arte o quienes tienen raíces italianas.",
    aprenderas: ["Conversar en restaurantes, viajes y encuentros sociales", "Entender el ritmo y la entonación italianos", "Leer textos breves y comprender canciones y películas", "Usar los verbos más frecuentes con seguridad", "Descubrir expresiones y cultura de Italia"],
    paraQuien: "Principiantes y estudiantes con base de otros idiomas romances que quieren avanzar rápido.",
    niveles: [
      ["Básico", "Presentaciones, números, comida, viajes y vida diaria."],
      ["Intermedio", "Conversaciones más largas, pasado y futuro, opiniones."],
      ["Avanzado", "Expresión fluida y comprensión de medios y literatura."]
    ],
    horarios: [
      ["Italiano Básico", "Lun – Mié", "17:00 – 18:30", "Presencial", 7],
      ["Italiano Intermedio", "Vie", "18:00 – 21:00", "Online en directo", 9]
    ],
    duracion: "Ciclos de 12 semanas", grupo: "Máx. 12 personas"
  },
  aleman: {
    nombre: "Alemán", saludo: "Hallo!", foto: IMG + "1780049921469-4afa2c328d4e" + Q,
    credito: "Pavel Moiseev", creditoUrl: "https://unsplash.com/photos/bicyclists-ride-through-a-modern-city-square-ev4QtMIMixc",
    lema: "Abre nuevas puertas en tu vida personal y profesional.",
    descripcion: "El alemán abre oportunidades académicas y laborales en Europa. Con una metodología clara y progresiva, dominas la estructura del idioma sin perder la conversación: gramática explicada de forma sencilla, práctica constante y vocabulario útil desde el inicio.",
    aprenderas: ["Comunicarte en situaciones de estudio, trabajo y viaje", "Entender la estructura de la oración alemana", "Ampliar vocabulario profesional y cotidiano", "Redactar textos y correos formales", "Prepararte para exámenes de nivel (opcional)"],
    paraQuien: "Estudiantes y profesionales que buscan becas, intercambio o empleo en países de habla alemana.",
    niveles: [
      ["Básico", "Presentarte, rutina diaria, compras y primeras estructuras."],
      ["Intermedio", "Opinar, argumentar y comprender textos de actualidad."],
      ["Avanzado", "Precisión gramatical y fluidez en contextos profesionales."]
    ],
    horarios: [
      ["Alemán Básico", "Mar – Jue", "17:30 – 19:00", "Presencial", 5],
      ["Alemán Intermedio", "Sáb", "09:00 – 12:00", "Online en directo", 3]
    ],
    duracion: "Ciclos de 12 semanas", grupo: "Máx. 10 personas"
  }
};

function renderCurso() {
  if (!document.getElementById("courseHero")) return; // solo en curso.php
  const id = new URLSearchParams(location.search).get("i") || "ingles";
  const c = CURSOS[id] || CURSOS.ingles;
  document.title = c.nombre + " · Language Schoolar";

  const hero = document.getElementById("courseHero");
  hero.style.backgroundImage = "url('" + c.foto + "')";
  hero.style.backgroundColor = "#285c4d";
  document.getElementById("cSaludo").textContent = c.saludo;
  document.getElementById("cNombre").textContent = "Curso de " + c.nombre;
  document.getElementById("cLema").textContent = c.lema;
  document.getElementById("cCredito").innerHTML = 'Foto: <a href="' + c.creditoUrl + '" target="_blank" rel="noopener">' + c.credito + '</a> en Unsplash';

  document.getElementById("cDesc").textContent = c.descripcion;
  document.getElementById("cQuien").textContent = c.paraQuien;
  document.getElementById("cAprenderas").innerHTML = c.aprenderas.map(t => "<li>" + t + "</li>").join("");
  document.getElementById("cNiveles").innerHTML = c.niveles.map((n, i) =>
    '<div class="level"><span class="num">Nivel ' + (i + 1) + '</span><h4>' + n[0] + '</h4><p>' + n[1] + '</p></div>').join("");
  document.getElementById("cHorarios").innerHTML = c.horarios.map(h =>
    "<tr><td>" + h[0] + "</td><td>" + h[1] + "</td><td>" + h[2] + "</td><td>" + h[3] + "</td><td>" + (h[4] > 0 ? '<span class="pill ok">' + h[4] + ' lugares</span>' : '<span class="pill warn">Lista de espera</span>') + "</td></tr>").join("");
  document.getElementById("cDuracion").textContent = c.duracion;
  document.getElementById("cGrupo").textContent = c.grupo;
  document.getElementById("cSide").textContent = c.nombre;
  document.getElementById("btnInscribir").href = "inscribirme.php?i=" + id;

  document.getElementById("cOtros").innerHTML = Object.entries(CURSOS).filter(([k]) => k !== id).map(([k, v]) =>
    '<a href="curso.php?i=' + k + '">' + v.nombre + " →</a>").join("");
}
document.addEventListener("DOMContentLoaded", renderCurso);
