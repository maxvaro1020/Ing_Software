<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Wireframes · Language Schoolar</title>
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/wireframes.css">
</head>
<body data-page="wireframes">
<div id="shell">

  <h1 class="page-title">Wireframes de baja fidelidad</h1>
  <p class="page-sub">Blanco y negro. Cada pantalla muestra el estado normal y los flujos alternos o de error. Los campos de captura son entradas; los mensajes son salidas.</p>

  <div class="wf-tabs">
    <a href="#cu01">CU-01</a><a href="#cu02">CU-02</a><a href="#cu03">CU-03</a><a href="#cu04">CU-04</a>
    <a href="#cu05">CU-05</a><a href="#cu06">CU-06</a><a href="#cu07">CU-07</a>
    <a href="cu01-registrar-alumno.php">→ Ir a prototipos</a>
  </div>

  <!-- ================= CU-01 ================= -->
  <section class="wf-section" id="cu01">
    <h2>CU-01 · Registrar un nuevo alumno <a href="cu01-registrar-alumno.php" style="font-size:.75rem">(ver prototipo)</a></h2>
    <p class="note">RF-01. Entradas: Nombre, Apellidos, Correo, Teléfono → ALUMNO. Salida: matrícula generada (calculada). Alterno A1: campos obligatorios vacíos.</p>
    <div class="wf-row">
      <div class="wf" data-title="Registro de alumnos · CU-01 · Estado normal">
        <div class="screen">
          <div class="bar"><b>Language School</b><span>CU-01</span></div>
          <h3>Formulario de Registro de Alumnos</h3>
          <div class="state">Estado mostrado: normal</div>
          <div class="two">
            <div class="f"><label>Nombre</label><div class="box">Nombre</div></div>
            <div class="f"><label>Apellidos</label><div class="box">Apellidos</div></div>
            <div class="f"><label>Correo</label><div class="box">Correo</div></div>
            <div class="f"><label>Teléfono</label><div class="box">Teléfono</div></div>
          </div>
          <span class="btn-wf">Registrar</span>
          <div class="msg"><b>Alumno registrado</b>Matrícula generada: AL-XXXX</div>
        </div>
      </div>
      <div class="wf" data-title="Registro de alumnos · CU-01 · A1 datos incompletos">
        <div class="screen">
          <div class="bar"><b>Language School</b><span>CU-01</span></div>
          <h3>Formulario de Registro de Alumnos</h3>
          <div class="state">Estado mostrado: validación de campos</div>
          <div class="two">
            <div class="f"><label>Nombre</label><div class="box red">Nombre</div><small>Revisa este campo.</small></div>
            <div class="f"><label>Apellidos</label><div class="box red">Apellidos</div><small>Revisa este campo.</small></div>
            <div class="f"><label>Correo</label><div class="box red">Correo</div><small>Revisa este campo.</small></div>
            <div class="f"><label>Teléfono</label><div class="box red">Teléfono</div><small>Revisa este campo.</small></div>
          </div>
          <span class="btn-wf">Registrar</span>
          <div class="msg err"><b>Error</b>Campo obligatorio no completado.</div>
        </div>
      </div>
    </div>
  </section>

  <!-- ================= CU-02 ================= -->
  <section class="wf-section" id="cu02">
    <h2>CU-02 · Asignar nivel al alumno <a href="cu02-asignar-nivel.php" style="font-size:.75rem">(ver prototipo)</a></h2>
    <p class="note">Entradas: alumno registrado y nivel. Salida: confirmación o error si falta alumno o nivel.</p>
    <div class="wf-row">
      <div class="wf" data-title="Asignar nivel · CU-02 · Normal">
        <div class="screen">
          <div class="bar"><b>Language School</b><span>CU-02</span></div>
          <h3>CU-02 · Asignar nivel al alumno</h3>
          <div class="f"><label>Alumno</label><div class="box">Buscar / seleccionar alumno registrado</div></div>
          <div class="f"><label>Nivel</label><div class="box">Seleccionar nivel disponible</div></div>
          <span class="btn-wf">Asignar nivel</span>
          <div class="msg"><b>Salida / estado</b>Nivel asignado al alumno.</div>
        </div>
      </div>
      <div class="wf" data-title="Asignar nivel · CU-02 · Error">
        <div class="screen">
          <div class="bar"><b>Language School</b><span>CU-02</span></div>
          <h3>CU-02 · Asignar nivel al alumno</h3>
          <div class="f"><label>Alumno</label><div class="box red">Buscar / seleccionar alumno registrado</div></div>
          <div class="f"><label>Nivel</label><div class="box red">Seleccionar nivel disponible</div></div>
          <span class="btn-wf">Asignar nivel</span>
          <div class="msg err"><b>Error</b>No se seleccionó alumno o nivel.</div>
        </div>
      </div>
    </div>
  </section>

  <!-- ================= CU-03 ================= -->
  <section class="wf-section" id="cu03">
    <h2>CU-03 · Panel de inscripción de grupos <a href="cu03-inscripcion.php" style="font-size:.75rem">(ver prototipo)</a></h2>
    <p class="note">RF-03, RF-04, RF-05. Cupos disponibles es un dato consultado (GRUPO.cupo_disponible). Alterno A1: grupo lleno → lista de espera (LISTA_ESPERA).</p>
    <div class="wf-row">
      <div class="wf" data-title="Inscripción de grupos · CU-03 · Grupo con cupo">
        <div class="screen">
          <div class="bar"><b>Language School</b><span>CU-03</span></div>
          <h3>Panel de Inscripción de Grupos</h3>
          <div class="state">Estado mostrado: grupo con cupos</div>
          <div class="f"><label>Alumno</label><div class="box">Seleccionar alumno ⌄</div></div>
          <div class="f"><label>Grupo</label><div class="box">Seleccionar grupo ⌄</div></div>
          <div class="f"><div class="box" style="display:inline-block;width:auto;height:auto;padding:3px 8px">Cupos disponibles: 8</div></div>
          <span class="btn-wf">Inscribir</span>
          <div class="msg"><b>Inscripción realizada con éxito</b>Cupo actualizado.</div>
        </div>
      </div>
      <div class="wf" data-title="Inscripción de grupos · CU-03 · A1 grupo lleno">
        <div class="screen">
          <div class="bar"><b>Language School</b><span>CU-03</span></div>
          <h3>Panel de Inscripción de Grupos</h3>
          <div class="state">Estado alternativo: grupo lleno</div>
          <div class="f"><label>Alumno</label><div class="box">Seleccionar alumno ⌄</div></div>
          <div class="f"><label>Grupo</label><div class="box">Seleccionar grupo ⌄</div></div>
          <div class="f"><div class="box" style="display:inline-block;width:auto;height:auto;padding:3px 8px">Cupos disponibles: 0</div></div>
          <div class="msg warn"><b>Grupo lleno: Agregar a lista de espera</b>Posición en lista: #N (la determina el sistema)</div>
          <span class="btn-wf">Agregar a lista de espera</span>
        </div>
      </div>
    </div>
  </section>

  <!-- ================= CU-04 ================= -->
  <section class="wf-section" id="cu04">
    <h2>CU-04 · Consultar expediente unificado <a href="cu04-expediente.php" style="font-size:.75rem">(ver prototipo)</a></h2>
    <p class="note">Entrada: matrícula/ID o nombre. Salida: expediente unificado; sin coincidencia se informa que no se encontraron datos.</p>
    <div class="wf-row">
      <div class="wf" data-title="Expediente · CU-04 · Con resultados">
        <div class="screen">
          <div class="bar"><b>Language School</b><span>CU-04</span></div>
          <h3>CU-04 · Consultar expediente unificado</h3>
          <div class="f"><label>Alumno</label><div class="box">Buscar por matrícula/ID o nombre</div></div>
          <span class="btn-wf">Consultar expediente</span>
          <div class="f"><label>Expediente</label><div class="box tall">Datos académicos y observaciones relacionadas</div></div>
          <div class="f"><label>Inscripción / nivel</label><div class="box">Información relacionada disponible</div></div>
        </div>
      </div>
      <div class="wf" data-title="Expediente · CU-04 · Sin coincidencias">
        <div class="screen">
          <div class="bar"><b>Language School</b><span>CU-04</span></div>
          <h3>CU-04 · Consultar expediente unificado</h3>
          <div class="f"><label>Alumno</label><div class="box red">AL-9999</div></div>
          <span class="btn-wf">Consultar expediente</span>
          <div class="msg warn"><b>Sin coincidencias</b>No se encontraron datos.</div>
        </div>
      </div>
    </div>
  </section>

  <!-- ================= CU-05 ================= -->
  <section class="wf-section" id="cu05">
    <h2>CU-05 · Registrar preguntas frecuentes <a href="cu05-registrar-faq.php" style="font-size:.75rem">(ver prototipo)</a></h2>
    <p class="note">Entradas: pregunta, respuesta, categoría, estado. Alterno: campos obligatorios sin completar.</p>
    <div class="wf-row">
      <div class="wf" data-title="Registrar FAQ · CU-05 · Normal">
        <div class="screen">
          <div class="bar"><b>Language School</b><span>CU-05</span></div>
          <h3>CU-05 · Registrar preguntas frecuentes</h3>
          <div class="f"><label>Pregunta</label><div class="box">Escribir pregunta frecuente</div></div>
          <div class="f"><label>Respuesta</label><div class="box tall">Escribir respuesta</div></div>
          <div class="two">
            <div class="f"><label>Categoría</label><div class="box">Seleccionar o indicar</div></div>
            <div class="f"><label>Estado</label><div class="box">Estado de publicación</div></div>
          </div>
          <span class="btn-wf">Guardar pregunta</span>
          <div class="msg"><b>Pregunta registrada</b>Categorización confirmada.</div>
        </div>
      </div>
      <div class="wf" data-title="Registrar FAQ · CU-05 · Error">
        <div class="screen">
          <div class="bar"><b>Language School</b><span>CU-05</span></div>
          <h3>CU-05 · Registrar preguntas frecuentes</h3>
          <div class="f"><label>Pregunta</label><div class="box red"></div><small>Campo obligatorio.</small></div>
          <div class="f"><label>Respuesta</label><div class="box tall red"></div><small>Campo obligatorio.</small></div>
          <span class="btn-wf">Guardar pregunta</span>
          <div class="msg err"><b>Error</b>Completa los campos obligatorios.</div>
        </div>
      </div>
    </div>
  </section>

  <!-- ================= CU-06 ================= -->
  <section class="wf-section" id="cu06">
    <h2>CU-06 · Consultar preguntas frecuentes <a href="cu06-consultar-faq.php" style="font-size:.75rem">(ver prototipo)</a></h2>
    <p class="note">Entradas: texto y categoría. Salida: listado de preguntas activas; sin coincidencias se informa y se permite limpiar la búsqueda.</p>
    <div class="wf-row">
      <div class="wf" data-title="Consultar FAQ · CU-06 · Con resultados">
        <div class="screen">
          <div class="bar"><b>Language School</b><span>CU-06</span></div>
          <h3>CU-06 · Consultar preguntas frecuentes</h3>
          <div class="f"><label>Buscar pregunta</label><div class="box">Texto o palabra clave</div></div>
          <div class="f"><label>Categoría</label><div class="box">Filtrar por categoría</div></div>
          <span class="btn-wf">Buscar</span>
          <div class="f"><label>Preguntas encontradas</label><div class="box tall">Listado de preguntas y respuestas activas</div></div>
        </div>
      </div>
      <div class="wf" data-title="Consultar FAQ · CU-06 · Sin coincidencias">
        <div class="screen">
          <div class="bar"><b>Language School</b><span>CU-06</span></div>
          <h3>CU-06 · Consultar preguntas frecuentes</h3>
          <div class="f"><label>Buscar pregunta</label><div class="box">xyz</div></div>
          <span class="btn-wf">Buscar</span><span class="btn-wf light">Limpiar búsqueda</span>
          <div class="msg warn"><b>Sin coincidencias</b>No se encontraron preguntas.</div>
        </div>
      </div>
    </div>
  </section>

  <!-- ================= CU-07 ================= -->
  <section class="wf-section" id="cu07">
    <h2>CU-07 · Módulo de validación de pagos <a href="cu07-validar-pago.php" style="font-size:.75rem">(ver prototipo)</a></h2>
    <p class="note">RF-10, RF-11. Entrada: estado Validado/Rechazado y observaciones. Salida: comprobante, estado de pago y estatus de matrícula. Alterno A1: rechazo con motivo.</p>
    <div class="wf-row">
      <div class="wf" data-title="Validación de pagos · CU-07 · Validado">
        <div class="screen">
          <div class="bar"><b>Language School</b><span>CU-07</span></div>
          <h3>Módulo de Validación de Pagos</h3>
          <div class="split">
            <div><label style="font-size:.62rem;font-weight:600">Pagos pendientes</label>
              <div class="list"><div class="sel">Pago pendiente</div><div>Pago pendiente</div><div>Pago pendiente</div></div></div>
            <div>
              <label style="font-size:.62rem;font-weight:600">Comprobante adjunto</label>
              <div class="viewer">Vista del comprobante adjunto</div>
              <label style="font-size:.62rem;font-weight:600">Estado del pago</label>
              <div class="radios"><div>● Validado</div><div>○ Rechazado</div></div>
              <div class="msg"><b>Pago validado</b>Matrícula actualizada.</div>
            </div>
          </div>
          <span class="btn-wf">Confirmar decisión</span>
        </div>
      </div>
      <div class="wf" data-title="Validación de pagos · CU-07 · A1 rechazado">
        <div class="screen">
          <div class="bar"><b>Language School</b><span>CU-07</span></div>
          <h3>Módulo de Validación de Pagos</h3>
          <div class="split">
            <div><label style="font-size:.62rem;font-weight:600">Pagos pendientes</label>
              <div class="list"><div class="sel">Pago pendiente</div><div>Pago pendiente</div><div>Pago pendiente</div></div></div>
            <div>
              <label style="font-size:.62rem;font-weight:600">Comprobante adjunto</label>
              <div class="viewer">Vista del comprobante adjunto</div>
              <div class="radios"><div>○ Validado</div><div>● Rechazado</div></div>
              <div class="f" style="margin:0 0 6px"><label>Observaciones (obligatorio)</label><div class="box tall red">Motivo del rechazo</div></div>
              <div class="msg err"><b>Comprobante rechazado</b>Notificación enviada. Matrícula sigue PENDIENTE.</div>
            </div>
          </div>
          <span class="btn-wf">Confirmar decisión</span>
        </div>
      </div>
    </div>
  </section>

</div>
<script src="../js/app.js"></script>
</body>
</html>
