<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CU-04 · Consultar expediente unificado</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body data-page="cu04">
<div id="shell">

  <h1 class="page-title">CU-04 · Consultar expediente unificado</h1>
  <p class="page-sub">Busca por matrícula o nombre. Alterno: sin coincidencia → se informa que no se encontraron datos.</p>

  <form id="formExp" class="card" novalidate>
    <div class="field">
      <label for="busca">Alumno</label>
      <input type="search" id="busca" placeholder="Buscar por matrícula/ID o nombre (prueba: AL-0421)">
    </div>
    <div class="actions">
      <button class="btn" type="submit">Consultar expediente</button>
      <a class="btn secondary" href="panel.php">Volver al menú</a>
    </div>
    <div id="out" class="alert" role="status"></div>
    <div id="resultado" style="display:none;margin-top:16px"></div>
  </form>


  <details class="trace">
    <summary>Ver trazabilidad CU → paso → dato → tabla/campo → control</summary>
    <table class="data">
      <thead><tr><th>Dato</th><th>E/S</th><th>Tipo</th><th>Tabla / campo</th><th>Control</th></tr></thead>
      <tbody>
        <tr><td>Matrícula/ID o nombre</td><td>Entrada</td><td><span class="tag temp">Temporal</span></td><td>N/A (criterio de búsqueda)</td><td>Campo de búsqueda</td></tr>
        <tr><td>Datos del alumno</td><td>Salida</td><td><span class="tag query">Consultado</span></td><td>ALUMNO.* (nombre, apellidos, correo, teléfono)</td><td>Tabla de expediente</td></tr>
        <tr><td>Nivel e inscripción</td><td>Salida</td><td><span class="tag query">Consultado</span></td><td>INSCRIPCION / nivel (confirmar en E2)</td><td>Tabla de expediente</td></tr>
        <tr><td>Estado de pago</td><td>Salida</td><td><span class="tag query">Consultado</span></td><td>PAGO.estado</td><td>Tabla de expediente</td></tr>
        <tr><td>Sin coincidencias</td><td>Salida</td><td><span class="tag temp">Temporal</span></td><td>N/A</td><td>Banner amarillo</td></tr>
      </tbody>
    </table>
    <p class="hint" style="margin-top:8px;font-size:.72rem;color:var(--muted)">Los campos marcados «confirmar en E2» no aparecen en la matriz original: verifica que existan exactamente así en tu E2 antes de entregar; si no existen, corrige E2 primero.</p>
  </details>

</div>
<script src="../js/app.js"></script>
</body>
</html>
