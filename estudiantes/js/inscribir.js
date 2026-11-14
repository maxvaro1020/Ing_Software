/* ============================================================
   Language Schoolar · Solicitud de inscripción (pública)
   Prototipo sin backend: la solicitud se guarda en localStorage del
   navegador como demostración. En el sistema real la recibe la capa
   de servicios y el personal la procesa con CU-01 (alta de alumno) y
   CU-03 (inscripción / lista de espera).
   ============================================================ */
let LISTA_ESPERA_DEMO = 3;

function guardarSolicitud(s) {
  try {
    const arr = JSON.parse(localStorage.getItem("ls_solicitudes") || "[]");
    arr.push(s);
    localStorage.setItem("ls_solicitudes", JSON.stringify(arr));
  } catch (e) { /* almacenamiento no disponible: se ignora */ }
}

function initInscripcion() {
  const id = new URLSearchParams(location.search).get("i") || "ingles";
  const c = CURSOS[id] || CURSOS.ingles;
  document.title = "Inscripción · " + c.nombre + " · Language Schoolar";

  document.getElementById("eCurso").textContent = c.nombre;
  document.getElementById("eCursoSide").textContent = c.nombre;
  document.getElementById("eVolver").href = "curso.php?i=" + id;

  // Lista de grupos del curso (se reutilizan los horarios de la página del curso)
  const sel = document.getElementById("grupo");
  c.horarios.forEach((h, i) => {
    const o = document.createElement("option");
    o.value = i;
    o.textContent = h[0] + " · " + h[1] + " " + h[2] + " · " + h[3] + (h[4] > 0 ? "" : " (lista de espera)");
    sel.appendChild(o);
  });

  function refreshResumen() {
    const h = c.horarios[sel.value];
    document.getElementById("rGrupo").textContent = h ? h[0] : "—";
    document.getElementById("rHorario").textContent = h ? h[1] + " · " + h[2] : "—";
    document.getElementById("rModalidad").textContent = h ? h[3] : "—";
    const d = document.getElementById("rDisp");
    d.innerHTML = !h ? "—" : (h[4] > 0 ? '<span class="pill ok">' + h[4] + ' lugares disponibles</span>' : '<span class="pill warn">Grupo lleno · lista de espera</span>');
    const nt = document.getElementById("notice");
    if (h && h[4] === 0) {
      nt.className = "notice warn show";
      nt.innerHTML = "<strong>Grupo lleno</strong>Este grupo no tiene lugares. Si continúas, quedarás en lista de espera y te avisaremos cuando haya cupo.";
      document.getElementById("btnEnviar").textContent = "Unirme a la lista de espera";
    } else {
      nt.className = "notice";
      document.getElementById("btnEnviar").textContent = "Enviar solicitud ↗";
    }
  }
  sel.addEventListener("change", refreshResumen);
  refreshResumen();

  const emailOk = v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
  function bad(idField, flag, msg) {
    const f = document.getElementById(idField);
    f.classList.toggle("bad", flag);
    if (msg) f.querySelector(".em").textContent = msg;
  }

  document.getElementById("enrollForm").addEventListener("submit", e => {
    e.preventDefault();
    const v = k => document.getElementById(k).value.trim();
    let errores = 0;

    [["nombre", "f-nombre"], ["apellidos", "f-apellidos"], ["telefono", "f-telefono"]].forEach(([k, f]) => {
      const mal = !v(k); bad(f, mal, "Campo obligatorio no completado."); if (mal) errores++;
    });
    const mc = !v("correo") || !emailOk(v("correo"));
    bad("f-correo", mc, v("correo") ? "Escribe un correo válido." : "Campo obligatorio no completado."); if (mc) errores++;
    const mg = sel.value === ""; bad("f-grupo", mg, "Selecciona un grupo."); if (mg) errores++;
    const chk = document.getElementById("acepto").checked;
    document.getElementById("chkRow").classList.toggle("bad", !chk); if (!chk) errores++;

    const nt = document.getElementById("notice");
    if (errores) {
      nt.className = "notice err show";
      nt.innerHTML = "<strong>Faltan datos</strong>Revisa los campos marcados en rojo para continuar.";
      return;
    }

    const h = c.horarios[sel.value];
    const lleno = h[4] === 0;
    const folio = "INS-" + String(Math.floor(1000 + Math.random() * 9000));
    const pos = lleno ? LISTA_ESPERA_DEMO++ : null;
    guardarSolicitud({ folio, curso: c.nombre, grupo: h[0], horario: h[1] + " " + h[2], modalidad: h[3],
      nombre: v("nombre"), apellidos: v("apellidos"), correo: v("correo"), telefono: v("telefono"),
      estado: lleno ? "Lista de espera #" + pos : "Solicitud recibida", fecha: new Date().toISOString() });

    document.getElementById("formArea").style.display = "none";
    const done = document.getElementById("done");
    done.classList.add("show");
    document.getElementById("doneTitle").textContent = lleno ? "Estás en la lista de espera" : "¡Solicitud enviada!";
    document.getElementById("doneFolio").textContent = "Folio " + folio;
    document.getElementById("doneText").textContent = lleno
      ? "Quedaste en la posición #" + pos + " de " + h[0] + ". Te avisaremos por correo (" + v("correo") + ") cuando se libere un lugar."
      : "Gracias, " + v("nombre") + ". Reservamos tu lugar en " + h[0] + " (" + h[1] + " " + h[2] + "). Servicios Escolares te contactará por correo para confirmar tu inscripción y el pago.";
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
}
document.addEventListener("DOMContentLoaded", initInscripcion);
