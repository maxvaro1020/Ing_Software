/* ============================================================
   Language Schoolar · Lógica de interfaz (prototipo sin backend)
   - Sesión simulada (sessionStorage)
   - Navegación lateral común filtrada por rol
   - Simulador de estados (normal / error / advertencia / éxito)
   - Lógica específica por pantalla (data-page en <body>)
   NOTA: los datos son de demostración. La interfaz NO accede a la BD;
   en la arquitectura real las llamadas pasan por la capa de servicios.
   ============================================================ */

/* ---------- Configuración de módulos y roles ----------
   IMPORTANTE: los permisos exactos deben tomarse de la matriz de roles de E1.
   Esta tabla es un marcador para el prototipo: ajústala con tu E1. */
const MODULES = [
  { id: "cu01", cu: "CU-01", label: "Alumnos",          sub: "Registrar alumno",          href: "cu01-registrar-alumno.php",   critical: true,  desc: "Alta de un nuevo alumno y generación de matrícula." },
  { id: "cu02", cu: "CU-02", label: "Niveles",          sub: "Asignar nivel",             href: "cu02-asignar-nivel.php",      critical: false, desc: "Asignar el nivel de idioma a un alumno registrado." },
  { id: "cu03", cu: "CU-03", label: "Inscripciones",    sub: "Cupos / lista de espera",   href: "cu03-inscripcion.php",        critical: true,  desc: "Inscribir en un grupo validando cupo o lista de espera." },
  { id: "cu04", cu: "CU-04", label: "Expediente",       sub: "Consulta unificada",        href: "cu04-expediente.php",         critical: false, desc: "Consultar la información unificada del alumno." },
  { id: "cu05", cu: "CU-05", label: "Registrar FAQ",    sub: "Registrar y categorizar",   href: "cu05-registrar-faq.php",      critical: false, desc: "Registrar y categorizar preguntas frecuentes." },
  { id: "cu06", cu: "CU-06", label: "Preguntas frec.",  sub: "Consultar preguntas",       href: "cu06-consultar-faq.php",      critical: false, desc: "Buscar y consultar preguntas frecuentes activas." },
  { id: "cu07", cu: "CU-07", label: "Pagos",            sub: "Validar / rechazar pago",   href: "cu07-validar-pago.php",       critical: true,  desc: "Revisar comprobantes y actualizar estado de pago y matrícula." }
];

const ROLES = {
  "Estudiante": ["cu04", "cu06"],
  "Demostración (todos los módulos)": ["cu01", "cu02", "cu03", "cu04", "cu05", "cu06", "cu07"],
  "Recepción / Servicios Escolares": ["cu01", "cu02", "cu04", "cu06"],
  "Coordinación":                    ["cu02", "cu03", "cu04", "cu05", "cu06"],
  "Caja / Servicios Financieros":    ["cu04", "cu06", "cu07"]
};

/* ---------- Sesión ---------- */
const Session = {
  get() { try { return JSON.parse(sessionStorage.getItem("ls_session")); } catch (e) { return null; } },
  set(s) { try { sessionStorage.setItem("ls_session", JSON.stringify(s)); } catch (e) {} },
  clear() { try { sessionStorage.removeItem("ls_session"); } catch (e) {} }
};

/* ---------- Layout común (barra superior + navegación lateral) ---------- */
function buildLayout() {
  const page = document.body.dataset.page;
  if (!page) return;
  const s = Session.get();
  if (!s) { location.href = "index.php"; return; }

  const allowed = ROLES[s.role] || [];
  const nav = ['<a href="panel.php" class="' + (page === "menu" ? "active" : "") + '">Inicio</a>']
    .concat(MODULES.filter(m => allowed.includes(m.id)).map(m =>
      '<a href="' + m.href + '" class="' + (page === m.id ? "active" : "") + '">' + m.label + '<small>' + m.cu + ' · ' + m.sub + '</small></a>'))
    .concat(s.role === "Estudiante" ? [] : ['<a href="wireframes.php" class="' + (page === "wireframes" ? "active" : "") + '">Wireframes<small>Baja fidelidad CU-01 a CU-07</small></a>'])
    .concat(s.role === "Estudiante" ? [] : ['<a href="trazabilidad.php" class="' + (page === "trazabilidad" ? "active" : "") + '">Trazabilidad<small>Matriz, IA y conclusión</small></a>'])
    .concat(s.role === "Estudiante" ? [] : ['<a href="flujo.php" class="' + (page === "flujo" ? "active" : "") + '">Flujo de navegación<small>Principal y alternos</small></a>'])
    .join("");

  const shell = document.getElementById("shell");
  const inner = shell.innerHTML;
  shell.innerHTML =
    '<header class="topbar"><div class="brand">Language Schoolar</div>' +
    '<div class="user"><span>' + escapeHtml(s.email) + ' · ' + escapeHtml(s.role) + '</span><a href="#" id="logout">Cerrar sesión</a></div></header>' +
    '<div class="layout"><nav class="sidebar">' + nav + '</nav><main class="content">' + inner + '</main></div>';

  document.getElementById("logout").addEventListener("click", e => { e.preventDefault(); Session.clear(); location.href = "index.php"; });

  const wf = MODULES.find(m => m.id === page);
  if (wf) {
    const sub = document.querySelector(".page-sub");
    if (sub) sub.insertAdjacentHTML("afterend", '<p style="margin:-12px 0 16px;font-size:.8rem"><a href="wireframes.php#' + wf.id + '">Ver wireframe de ' + wf.cu + ' →</a></p>');
  }

  // Control de acceso: si el rol no tiene el módulo, se redirige al menú
  const mod = MODULES.find(m => m.id === page);
  if (mod && !allowed.includes(mod.id)) {
    document.querySelector(".content").innerHTML =
      '<div class="alert err show"><strong>Acceso no permitido</strong><span>Tu rol no tiene acceso a este módulo. <a href="panel.php">Volver al menú</a></span></div>';
  }
}

function escapeHtml(t) {
  return String(t).replace(/[&<>"']/g, c => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c]));
}

/* ---------- Utilidades de interfaz ---------- */
function showAlert(id, type, title, text) {
  const el = document.getElementById(id);
  if (!el) return;
  el.className = "alert show " + type;
  el.innerHTML = "<strong>" + title + "</strong><span>" + text + "</span>";
}
function hideAlert(id) { const el = document.getElementById(id); if (el) el.className = "alert"; }
function setInvalid(fieldId, bad) {
  const f = document.getElementById(fieldId);
  if (f) f.classList.toggle("invalid", bad);
}

/* Barra "Simular estado": permite mostrar cada flujo (normal / alterno / error) */
function bindStateBar(handlers) {
  const bar = document.querySelector(".state-bar");
  if (!bar) return;
  bar.querySelectorAll("button").forEach(b => b.addEventListener("click", () => {
    bar.querySelectorAll("button").forEach(x => x.classList.remove("on"));
    b.classList.add("on");
    const fn = handlers[b.dataset.state];
    if (fn) fn();
  }));
}

/* ============================================================
   PÁGINAS
   ============================================================ */

/* ---------- Landing / login ---------- */
function initLanding() {
  // Roles en el selector
  const sel = document.getElementById("role");
  if (sel) Object.keys(ROLES).forEach(r => { const o = document.createElement("option"); o.textContent = r; sel.appendChild(o); });

  // Pestañas iniciar sesión / crear cuenta
  document.querySelectorAll(".tabs button").forEach(b => b.addEventListener("click", () => {
    document.querySelectorAll(".tabs button").forEach(x => x.classList.remove("on"));
    document.querySelectorAll(".pane").forEach(x => x.classList.remove("on"));
    b.classList.add("on");
    document.getElementById(b.dataset.pane).classList.add("on");
  }));

  // Mostrar/ocultar panel con "Mi cuenta"
  const panel = document.getElementById("loginPanel");
  document.getElementById("accountBtn").addEventListener("click", () => {
    panel.style.display = panel.style.display === "none" ? "block" : "none";
  });

  // Login (flujo principal y alterno)
  document.getElementById("loginForm").addEventListener("submit", e => {
    e.preventDefault();
    const email = document.getElementById("email").value.trim();
    const pass = document.getElementById("pass").value;
    const role = sel.value;
    const err = document.getElementById("loginErr");
    if (!email || !pass || !role) {
      err.textContent = "Completa correo, contraseña y rol para continuar.";
      err.classList.add("show");
      return;
    }
    Session.set({ email, role });
    location.href = "panel.php";
  });

  // Mostrar contraseña
  document.getElementById("togglePass").addEventListener("click", () => {
    const p = document.getElementById("pass");
    p.type = p.type === "password" ? "text" : "password";
  });

  // Pregunta desde landing (formulario "¿Te queda alguna duda?")
  document.getElementById("askForm").addEventListener("submit", e => {
    e.preventDefault();
    const mail = document.getElementById("askMail").value.trim();
    const q = document.getElementById("askQ").value.trim();
    const chk = document.getElementById("askChk").checked;
    const ok = document.getElementById("askOk"), bad = document.getElementById("askBad");
    ok.classList.remove("show"); bad.classList.remove("show");
    if (!mail || !q || !chk) { bad.classList.add("show"); return; }
    ok.classList.add("show");
    e.target.reset();
  });
}

/* ---------- Menú principal ---------- */
function initMenu() {
  const s = Session.get();
  const allowed = ROLES[s.role] || [];
  const grid = document.getElementById("menuGrid");
  grid.innerHTML = MODULES.filter(m => allowed.includes(m.id)).map(m =>
    '<a class="menu-card ' + (m.critical ? "critical" : "") + '" href="' + m.href + '"><div class="cu">' + m.cu + (m.critical ? " · CRÍTICO" : "") +
    '</div><h3>' + m.label + '</h3><p>' + m.desc + '</p></a>').join("") +
    (s.role === "Estudiante" ? "" : '<a class="menu-card" href="wireframes.php"><div class="cu">DISEÑO</div><h3>Wireframes</h3><p>Baja fidelidad de CU-01 a CU-07 con flujos alternos.</p></a>' +
    '<a class="menu-card" href="trazabilidad.php"><div class="cu">DOCUMENTACIÓN</div><h3>Trazabilidad</h3><p>Matriz CU–RF–Datos–BD–Interfaz, IA rechazada y conclusión.</p></a>' +
    '<a class="menu-card" href="flujo.php"><div class="cu">NAVEGACIÓN</div><h3>Flujo de navegación</h3><p>Flujo principal, alternos y errores.</p></a>');
}

/* ---------- CU-01 Registrar alumno ---------- */
function initCU01() {
  const required = ["nombre", "apellidos", "correo", "telefono"];
  const form = document.getElementById("formAlumno");
  const emailOk = v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);

  function validate() {
    let missing = [];
    required.forEach(id => {
      const v = document.getElementById(id).value.trim();
      let bad = !v;
      const msg = document.querySelector("#f-" + id + " .error-msg");
      if (id === "correo" && v && !emailOk(v)) { bad = true; msg.textContent = "Formato de correo no válido."; }
      else if (bad) { msg.textContent = "Campo obligatorio no completado."; }
      setInvalid("f-" + id, bad);
      if (bad) missing.push(id);
    });
    return missing;
  }

  form.addEventListener("submit", e => {
    e.preventDefault();
    const missing = validate();
    if (missing.length) {
      showAlert("out", "err", "Datos incompletos (A1)", "Revisa los campos marcados en rojo antes de registrar.");
      return;
    }
    // Paso 6-7: el sistema almacena en ALUMNO y genera matrícula (simulado)
    const n = String(Math.floor(1000 + Math.random() * 9000));
    document.getElementById("matricula").value = "AL-" + n;
    showAlert("out", "ok", "Alumno registrado", "Se generó la matrícula AL-" + n + ". El alumno queda listo para asignación de nivel/inscripción.");
  });

  document.getElementById("btnLimpiar").addEventListener("click", () => {
    form.reset(); required.forEach(id => setInvalid("f-" + id, false));
    document.getElementById("matricula").value = ""; hideAlert("out");
  });

  bindStateBar({
    normal: () => { form.reset(); required.forEach(id => setInvalid("f-" + id, false)); hideAlert("out"); document.getElementById("matricula").value = ""; },
    error: () => { form.reset(); validate(); showAlert("out", "err", "Datos incompletos (A1)", "Revisa los campos marcados en rojo antes de registrar."); },
    persist: () => { showAlert("out", "err", "Error de persistencia (A2)", "No se pudo guardar el registro. Intenta nuevamente; si persiste, contacta a soporte."); },
    ok: () => {
      ["nombre","apellidos","correo","telefono"].forEach((id, i) => document.getElementById(id).value = ["Ana", "López Pérez", "ana.lopez@correo.com", "7771234567"][i]);
      required.forEach(id => setInvalid("f-" + id, false));
      document.getElementById("matricula").value = "AL-0421";
      showAlert("out", "ok", "Alumno registrado", "Se generó la matrícula AL-0421. El alumno queda listo para asignación de nivel/inscripción.");
    }
  });
}

/* ---------- CU-02 Asignar nivel ---------- */
function initCU02() {
  const form = document.getElementById("formNivel");
  form.addEventListener("submit", e => {
    e.preventDefault();
    const a = document.getElementById("alumno").value, n = document.getElementById("nivel").value;
    setInvalid("f-alumno", !a); setInvalid("f-nivel", !n);
    if (!a || !n) { showAlert("out", "err", "Faltan datos", "Selecciona un alumno y un nivel para continuar."); return; }
    showAlert("out", "ok", "Nivel asignado", "Se asignó el nivel " + n + " al alumno seleccionado.");
  });
}

/* ---------- CU-03 Inscripción ---------- */
const GRUPOS = {
  "A1 · Inglés Básico · Lun-Mié 18:00 · Presencial": { cupo: 8, cap: 15 },
  "B2 · Inglés Intermedio · Mar-Jue 19:00 · Online": { cupo: 0, cap: 15 },
  "C1 · Francés Básico · Sáb 09:00 · Presencial":    { cupo: 4, cap: 12 }
};
let listaEsperaPos = 3;

function initCU03() {
  const selG = document.getElementById("grupo");
  Object.keys(GRUPOS).forEach(g => { const o = document.createElement("option"); o.textContent = g; selG.appendChild(o); });

  function refreshCupo() {
    const g = GRUPOS[selG.value];
    const badge = document.getElementById("cupoBadge"), meter = document.getElementById("meter");
    const btnIns = document.getElementById("btnInscribir"), btnLista = document.getElementById("btnLista");
    hideAlert("out");
    if (!g) { badge.className = "badge info"; badge.textContent = "Selecciona un grupo"; meter.firstElementChild.style.width = "0"; btnLista.style.display = "none"; btnIns.disabled = false; return; }
    const used = g.cap - g.cupo;
    meter.firstElementChild.style.width = Math.round(used / g.cap * 100) + "%";
    if (g.cupo > 0) {
      badge.className = "badge ok"; badge.textContent = g.cupo + " cupos disponibles de " + g.cap;
      meter.classList.remove("full"); btnLista.style.display = "none"; btnIns.disabled = false;
    } else {
      badge.className = "badge warn"; badge.textContent = "Grupo saturado · 0 cupos";
      meter.classList.add("full"); btnLista.style.display = "inline-block"; btnIns.disabled = true;
      showAlert("out", "warn", "Grupo lleno (A1)", "No hay cupo en este grupo. Puedes agregar al alumno a la lista de espera.");
    }
  }
  selG.addEventListener("change", refreshCupo);

  function needAlumno() {
    const a = document.getElementById("alumno").value;
    setInvalid("f-alumno", !a);
    if (!a) showAlert("out", "err", "Falta alumno", "Selecciona un alumno registrado y con nivel asignado.");
    return !!a;
  }

  document.getElementById("btnInscribir").addEventListener("click", () => {
    if (!needAlumno()) return;
    if (!selG.value) { setInvalid("f-grupo", true); showAlert("out", "err", "Falta grupo", "Selecciona un grupo con horario y modalidad."); return; }
    setInvalid("f-grupo", false);
    document.getElementById("modalIns").classList.add("show");
  });
  document.getElementById("cancelIns").addEventListener("click", () => document.getElementById("modalIns").classList.remove("show"));
  document.getElementById("okIns").addEventListener("click", () => {
    const g = GRUPOS[selG.value]; g.cupo--;
    document.getElementById("modalIns").classList.remove("show");
    refreshCupo();
    showAlert("out", "ok", "Inscripción realizada con éxito", "Se registró la inscripción y el cupo del grupo se actualizó a " + g.cupo + ".");
  });
  document.getElementById("btnLista").addEventListener("click", () => {
    if (!needAlumno()) return;
    showAlert("out", "warn", "Alumno agregado a lista de espera", "Posición en lista: #" + listaEsperaPos++ + " (calculada por el sistema).");
  });

  bindStateBar({
    normal:   () => { selG.selectedIndex = 1; refreshCupo(); },
    lleno:    () => { selG.value = Object.keys(GRUPOS)[1]; refreshCupo(); },
    error:    () => { document.getElementById("alumno").value = ""; selG.selectedIndex = 0; refreshCupo(); needAlumno(); }
  });
  refreshCupo();
}

/* ---------- CU-04 Expediente ---------- */
const EXPEDIENTES = {
  "AL-0421": { nombre: "Ana López Pérez", nivel: "Inglés Básico (A1)", insc: "Grupo A1 · Lun-Mié 18:00 · Presencial", pago: "Validado" },
  "AL-0388": { nombre: "Carlos Ramírez", nivel: "Francés Básico", insc: "Sin inscripción activa", pago: "Pendiente" }
};
function initCU04() {
  document.getElementById("formExp").addEventListener("submit", e => {
    e.preventDefault();
    const q = document.getElementById("busca").value.trim().toLowerCase();
    const hit = Object.entries(EXPEDIENTES).find(([k, v]) => k.toLowerCase() === q || v.nombre.toLowerCase().includes(q));
    const box = document.getElementById("resultado");
    if (!q || !hit) { box.style.display = "none"; showAlert("out", "warn", "Sin coincidencias", "No se encontraron datos para la búsqueda. Intenta con otra matrícula o nombre."); return; }
    hideAlert("out");
    const [id, d] = hit;
    box.style.display = "block";
    box.innerHTML = '<table class="data"><tbody>' +
      '<tr><th>Matrícula</th><td>' + id + '</td></tr><tr><th>Alumno</th><td>' + d.nombre + '</td></tr>' +
      '<tr><th>Nivel</th><td>' + d.nivel + '</td></tr><tr><th>Inscripción</th><td>' + d.insc + '</td></tr>' +
      '<tr><th>Estado de pago</th><td>' + d.pago + '</td></tr></tbody></table>';
  });
}

/* ---------- CU-05 Registrar FAQ ---------- */
function initCU05() {
  document.getElementById("formFaq").addEventListener("submit", e => {
    e.preventDefault();
    const ids = ["pregunta", "respuesta", "categoria"]; let bad = false;
    ids.forEach(id => { const v = document.getElementById(id).value.trim(); setInvalid("f-" + id, !v); if (!v) bad = true; });
    if (bad) { showAlert("out", "err", "Campos obligatorios", "Completa pregunta, respuesta y categoría."); return; }
    showAlert("out", "ok", "Pregunta registrada", "La pregunta se registró y categorizó correctamente.");
  });
}

/* ---------- CU-06 Consultar FAQ ---------- */
const FAQS = [
  { q: "¿Puedo empezar si no sé nada del idioma?", a: "Sí, hay cursos de iniciación desde cero.", c: "Inscripción" },
  { q: "¿Cómo sé qué nivel me corresponde?", a: "Se orienta con una valoración de nivel.", c: "Niveles" },
  { q: "¿Las clases son online o presenciales?", a: "Puedes elegir presencial u online en directo.", c: "Modalidad" },
  { q: "¿Cómo puedo inscribirme en un curso?", a: "Crea tu cuenta y explora los idiomas.", c: "Inscripción" }
];
function initCU06() {
  const sel = document.getElementById("categoria");
  [...new Set(FAQS.map(f => f.c))].forEach(c => { const o = document.createElement("option"); o.textContent = c; sel.appendChild(o); });
  function run() {
    const t = document.getElementById("texto").value.trim().toLowerCase(), c = sel.value;
    const r = FAQS.filter(f => (!t || (f.q + f.a).toLowerCase().includes(t)) && (!c || f.c === c));
    const box = document.getElementById("lista");
    if (!r.length) { box.innerHTML = ""; showAlert("out", "warn", "Sin coincidencias", "No se encontraron preguntas. Limpia la búsqueda para ver todas."); return; }
    hideAlert("out");
    box.innerHTML = r.map(f => '<details class="card" style="padding:12px 18px;margin-bottom:8px"><summary><strong>' + f.q + '</strong> <span class="badge info">' + f.c + '</span></summary><p style="margin-top:8px;color:#5b6b7c">' + f.a + '</p></details>').join("");
  }
  document.getElementById("formFaqQ").addEventListener("submit", e => { e.preventDefault(); run(); });
  document.getElementById("btnClear").addEventListener("click", () => { document.getElementById("texto").value = ""; sel.value = ""; run(); });
  run();
}

/* ---------- CU-07 Validar pago ---------- */
const PAGOS = [
  { id: "PG-1001", alumno: "Ana López Pérez (AL-0421)", monto: "$1,800.00", fecha: "28/10/2026", archivo: "comprobante_1001.pdf" },
  { id: "PG-1002", alumno: "Carlos Ramírez (AL-0388)", monto: "$1,800.00", fecha: "29/10/2026", archivo: "comprobante_1002.jpg" },
  { id: "PG-1003", alumno: "María Torres (AL-0402)",   monto: "$2,100.00", fecha: "30/10/2026", archivo: "comprobante_1003.png" }
];
let pagoSel = null;
function initCU07() {
  const list = document.getElementById("listaPagos");
  list.innerHTML = PAGOS.map(p => '<button type="button" data-id="' + p.id + '">' + p.id + '<small>' + p.alumno + '</small></button>').join("");
  list.querySelectorAll("button").forEach(b => b.addEventListener("click", () => {
    list.querySelectorAll("button").forEach(x => x.classList.remove("sel")); b.classList.add("sel");
    pagoSel = PAGOS.find(p => p.id === b.dataset.id);
    document.getElementById("visor").innerHTML = '<div class="receipt"><strong>Comprobante adjunto</strong><br>Folio: ' + pagoSel.id + '<br>Alumno: ' + pagoSel.alumno + '<br>Monto: ' + pagoSel.monto + '<br>Fecha: ' + pagoSel.fecha + '<br><em>' + pagoSel.archivo + '</em></div>';
    hideAlert("out"); setInvalid("f-estado", false); setInvalid("f-obs", false);
  }));

  const radios = document.querySelectorAll("input[name=estado]");
  const obsField = document.getElementById("f-obs");
  radios.forEach(r => r.addEventListener("change", () => {
    const rech = document.querySelector("input[name=estado]:checked").value === "Rechazado";
    document.getElementById("lblObs").innerHTML = rech ? 'Observaciones (motivo de rechazo) <span class="req">*</span>' : "Observaciones";
  }));

  document.getElementById("formPago").addEventListener("submit", e => {
    e.preventDefault();
    if (!pagoSel) { showAlert("out", "err", "Selecciona un pago", "Elige un pago pendiente de la lista para revisar su comprobante."); return; }
    const est = document.querySelector("input[name=estado]:checked");
    if (!est) { setInvalid("f-estado", true); showAlert("out", "err", "Falta decisión", "Selecciona Validado o Rechazado."); return; }
    setInvalid("f-estado", false);
    const obs = document.getElementById("obs").value.trim();
    if (est.value === "Rechazado" && !obs) { setInvalid("f-obs", true); showAlert("out", "err", "Falta el motivo (A1)", "Para rechazar un comprobante debes registrar el motivo en observaciones."); return; }
    setInvalid("f-obs", false);
    document.getElementById("modalPago").dataset.est = est.value;
    document.getElementById("modalTxt").textContent = "Vas a marcar el pago " + pagoSel.id + " como " + est.value + ".";
    document.getElementById("modalPago").classList.add("show");
  });
  document.getElementById("cancelPago").addEventListener("click", () => document.getElementById("modalPago").classList.remove("show"));
  document.getElementById("okPago").addEventListener("click", () => {
    const m = document.getElementById("modalPago"); m.classList.remove("show");
    if (m.dataset.est === "Validado") showAlert("out", "ok", "Pago validado", "PAGO.estado = Validado. Estatus de matrícula actualizado a ACTIVO (CU-11).");
    else showAlert("out", "err", "Comprobante rechazado", "Motivo registrado y notificación enviada. La matrícula se mantiene PENDIENTE.");
  });
}

/* ---------- Arranque ---------- */
document.addEventListener("DOMContentLoaded", () => {
  const page = document.body.dataset.page;
  if (page === "landing") return initLanding();
  buildLayout();
  const map = { menu: initMenu, cu01: initCU01, cu02: initCU02, cu03: initCU03, cu04: initCU04, cu05: initCU05, cu06: initCU06, cu07: initCU07 };
  if (map[page] && document.querySelector(".content") && !document.querySelector(".content > .alert.err.show")) map[page]();
});
