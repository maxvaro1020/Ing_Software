<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CU-03 · Panel de inscripción de grupos</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body data-page="cu03">
<div id="shell">

  <h1 class="page-title">CU-03 · Panel de inscripción de grupos</h1>
  <p class="page-sub">Actor: Servicios Escolares / Coordinación · RF-03, RF-04, RF-05 · Alterno A1: grupo saturado → lista de espera</p>

  <div class="legend">
    Tipo de dato:
    <span><span class="tag persist">Persistente</span></span>
    <span><span class="tag query">Consultado</span></span>
    <span><span class="tag calc">Calculado</span></span>
    <span><span class="tag temp">Temporal</span></span>
  </div>

  <div class="state-bar">
    Simular estado:
    <button data-state="normal" class="on">Grupo con cupo</button>
    <button data-state="lleno">A1 · Grupo lleno</button>
    <button data-state="error">Error · falta alumno</button>
  </div>

  <div class="card">
    <h2>Inscripción</h2>
    <div class="field" id="f-alumno">
      <label for="alumno">Alumno <span class="req">*</span> <span class="tag query">ALUMNO.id_alumno</span></label>
      <select id="alumno">
        <option value="">Seleccionar alumno (registrado y con nivel)</option>
        <option>AL-0421 · Ana López Pérez</option>
        <option>AL-0388 · Carlos Ramírez</option>
        <option>AL-0402 · María Torres</option>
      </select>
      <span class="error-msg">Selecciona un alumno registrado.</span>
    </div>

    <div class="field" id="f-grupo">
      <label for="grupo">Grupo, horario y modalidad <span class="req">*</span> <span class="tag query">GRUPO.id_grupo</span></label>
      <select id="grupo"><option value="">Seleccionar grupo</option></select>
      <span class="error-msg">Selecciona un grupo.</span>
    </div>

    <div class="field">
      <label>Cupos disponibles <span class="tag query">GRUPO.cupo_disponible</span></label>
      <div class="capacity">
        <span class="badge info" id="cupoBadge">Selecciona un grupo</span>
        <div class="meter" id="meter"><div style="width:0"></div></div>
      </div>
      <span class="hint">Dato consultado al sistema; no se captura.</span>
    </div>

    <div class="actions">
      <button class="btn" type="button" id="btnInscribir">Inscribir</button>
      <button class="btn warn" type="button" id="btnLista" style="display:none">Agregar a lista de espera</button>
      <a class="btn secondary" href="panel.php">Cancelar</a>
    </div>

    <div id="out" class="alert" role="status"></div>
  </div>

  <details class="trace">
    <summary>Ver trazabilidad CU → RF → paso → dato → tabla/campo → control</summary>
    <table class="data">
      <thead><tr><th>Paso</th><th>Dato</th><th>E/S</th><th>Tipo</th><th>Tabla / campo</th><th>Control</th></tr></thead>
      <tbody>
        <tr><td>1</td><td>Alumno seleccionado</td><td>Entrada</td><td><span class="tag query">Consultado</span></td><td>ALUMNO.id_alumno</td><td>Lista desplegable</td></tr>
        <tr><td>2-3</td><td>Grupo / horario / modalidad</td><td>Entrada</td><td><span class="tag query">Consultado</span></td><td>GRUPO.id_grupo</td><td>Lista desplegable</td></tr>
        <tr><td>4</td><td>Cupos disponibles</td><td>Salida</td><td><span class="tag query">Consultado</span></td><td>GRUPO.cupo_disponible</td><td>Badge + barra de ocupación</td></tr>
        <tr><td>5-6</td><td>Inscripción confirmada</td><td>Salida</td><td><span class="tag persist">Persistente</span></td><td>INSCRIPCION.id_inscripcion</td><td>Botón «Inscribir» + modal + banner verde</td></tr>
        <tr><td>A1</td><td>Estado «saturado»</td><td>Salida</td><td><span class="tag calc">Calculado</span></td><td>GRUPO.cupo_disponible = 0</td><td>Banner amarillo</td></tr>
        <tr><td>A1</td><td>Posición en lista</td><td>Salida</td><td><span class="tag persist">Persistente</span></td><td>LISTA_ESPERA.id_espera / posicion</td><td>Botón «Agregar a lista de espera» + mensaje</td></tr>
      </tbody>
    </table>
  </details>

  <!-- Modal de confirmación (paso 5) -->
  <div class="modal-bg" id="modalIns">
    <div class="modal">
      <h3>Confirmar inscripción</h3>
      <p>¿Deseas inscribir al alumno en el grupo seleccionado?</p>
      <div class="actions">
        <button class="btn" id="okIns" type="button">Confirmar</button>
        <button class="btn secondary" id="cancelIns" type="button">Cancelar</button>
      </div>
    </div>
  </div>

</div>
<script src="../js/app.js"></script>
</body>
</html>
