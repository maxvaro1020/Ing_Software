<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CU-02 · Asignar nivel al alumno</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body data-page="cu02">
<div id="shell">

  <h1 class="page-title">CU-02 · Asignar nivel al alumno</h1>
  <p class="page-sub">Selecciona un alumno registrado y el nivel disponible. Alterno: falta alumno o nivel → error.</p>

  <form id="formNivel" class="card" novalidate>
    <div class="field" id="f-alumno">
      <label for="alumno">Alumno <span class="req">*</span></label>
      <select id="alumno">
        <option value="">Buscar / seleccionar alumno registrado</option>
        <option>AL-0421 · Ana López Pérez</option>
        <option>AL-0388 · Carlos Ramírez</option>
        <option>AL-0402 · María Torres</option>
      </select>
      <span class="error-msg">Selecciona un alumno.</span>
    </div>
    <div class="field" id="f-nivel">
      <label for="nivel">Nivel <span class="req">*</span></label>
      <select id="nivel">
        <option value="">Seleccionar nivel disponible</option>
        <option>Básico</option>
        <option>Intermedio</option>
        <option>Avanzado</option>
      </select>
      <span class="error-msg">Selecciona un nivel.</span>
    </div>
    <div class="actions">
      <button class="btn" type="submit">Asignar nivel</button>
      <a class="btn secondary" href="panel.php">Cancelar</a>
    </div>
    <div id="out" class="alert" role="status"></div>
  </form>


  <details class="trace">
    <summary>Ver trazabilidad CU → paso → dato → tabla/campo → control</summary>
    <table class="data">
      <thead><tr><th>Dato</th><th>E/S</th><th>Tipo</th><th>Tabla / campo</th><th>Control</th></tr></thead>
      <tbody>
        <tr><td>Alumno seleccionado</td><td>Entrada</td><td><span class="tag query">Consultado</span></td><td>ALUMNO.id_alumno</td><td>Lista desplegable</td></tr>
        <tr><td>Nivel seleccionado</td><td>Entrada</td><td><span class="tag persist">Persistente</span></td><td>Tabla de nivel / campo de nivel del alumno (confirmar en E2)</td><td>Lista desplegable + botón «Asignar nivel»</td></tr>
        <tr><td>Confirmación o error</td><td>Salida</td><td><span class="tag temp">Temporal</span></td><td>N/A</td><td>Banner verde / rojo</td></tr>
      </tbody>
    </table>
    <p class="hint" style="margin-top:8px;font-size:.72rem;color:var(--muted)">Los campos marcados «confirmar en E2» no aparecen en la matriz original: verifica que existan exactamente así en tu E2 antes de entregar; si no existen, corrige E2 primero.</p>
  </details>

</div>
<script src="../js/app.js"></script>
</body>
</html>
