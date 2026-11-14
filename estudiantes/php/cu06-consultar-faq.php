<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CU-06 · Consultar preguntas frecuentes</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body data-page="cu06">
<div id="shell">

  <h1 class="page-title">CU-06 · Consultar preguntas frecuentes</h1>
  <p class="page-sub">Busca por texto o categoría. Alterno: sin coincidencias → se informa y se permite limpiar la búsqueda.</p>

  <form id="formFaqQ" class="card" novalidate>
    <div class="grid-2">
      <div class="field">
        <label for="texto">Buscar pregunta</label>
        <input type="search" id="texto" placeholder="Texto o palabra clave">
      </div>
      <div class="field">
        <label for="categoria">Categoría</label>
        <select id="categoria"><option value="">Todas</option></select>
      </div>
    </div>
    <div class="actions">
      <button class="btn" type="submit">Buscar</button>
      <button class="btn secondary" type="button" id="btnClear">Limpiar búsqueda</button>
      <a class="btn secondary" href="panel.php">Volver al menú</a>
    </div>
    <div id="out" class="alert" role="status"></div>
  </form>

  <h2 style="font-size:1rem;margin-bottom:10px">Preguntas encontradas</h2>
  <div id="lista"></div>


  <details class="trace">
    <summary>Ver trazabilidad CU → paso → dato → tabla/campo → control</summary>
    <table class="data">
      <thead><tr><th>Dato</th><th>E/S</th><th>Tipo</th><th>Tabla / campo</th><th>Control</th></tr></thead>
      <tbody>
        <tr><td>Texto y categoría</td><td>Entrada</td><td><span class="tag temp">Temporal</span></td><td>N/A (filtros)</td><td>Campo de búsqueda + lista de categoría</td></tr>
        <tr><td>Preguntas activas</td><td>Salida</td><td><span class="tag query">Consultado</span></td><td>Tabla de preguntas frecuentes (confirmar en E2)</td><td>Lista de preguntas desplegables</td></tr>
        <tr><td>Sin coincidencias</td><td>Salida</td><td><span class="tag temp">Temporal</span></td><td>N/A</td><td>Banner amarillo + botón «Limpiar búsqueda»</td></tr>
      </tbody>
    </table>
    <p class="hint" style="margin-top:8px;font-size:.72rem;color:var(--muted)">Los campos marcados «confirmar en E2» no aparecen en la matriz original: verifica que existan exactamente así en tu E2 antes de entregar; si no existen, corrige E2 primero.</p>
  </details>

</div>
<script src="../js/app.js"></script>
</body>
</html>
