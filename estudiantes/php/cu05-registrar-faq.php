<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CU-05 · Registrar preguntas frecuentes</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body data-page="cu05">
<div id="shell">

  <h1 class="page-title">CU-05 · Registrar preguntas frecuentes</h1>
  <p class="page-sub">Registrar y categorizar. Alterno: campos obligatorios sin completar → error.</p>

  <form id="formFaq" class="card" novalidate>
    <div class="field" id="f-pregunta">
      <label for="pregunta">Pregunta <span class="req">*</span></label>
      <input type="text" id="pregunta" placeholder="Escribir pregunta frecuente">
      <span class="error-msg">Campo obligatorio no completado.</span>
    </div>
    <div class="field" id="f-respuesta">
      <label for="respuesta">Respuesta <span class="req">*</span></label>
      <textarea id="respuesta" placeholder="Escribir respuesta"></textarea>
      <span class="error-msg">Campo obligatorio no completado.</span>
    </div>
    <div class="grid-2">
      <div class="field" id="f-categoria">
        <label for="categoria">Categoría <span class="req">*</span></label>
        <input type="text" id="categoria" placeholder="Seleccionar o indicar categoría">
        <span class="error-msg">Campo obligatorio no completado.</span>
      </div>
      <div class="field">
        <label for="estado">Estado</label>
        <select id="estado"><option>Activa</option><option>Inactiva</option></select>
      </div>
    </div>
    <div class="actions">
      <button class="btn" type="submit">Guardar pregunta</button>
      <a class="btn secondary" href="panel.php">Cancelar</a>
    </div>
    <div id="out" class="alert" role="status"></div>
  </form>


  <details class="trace">
    <summary>Ver trazabilidad CU → paso → dato → tabla/campo → control</summary>
    <table class="data">
      <thead><tr><th>Dato</th><th>E/S</th><th>Tipo</th><th>Tabla / campo</th><th>Control</th></tr></thead>
      <tbody>
        <tr><td>Pregunta, respuesta, categoría, estado</td><td>Entrada</td><td><span class="tag persist">Persistente</span></td><td>Tabla de preguntas frecuentes (confirmar en E2)</td><td>Inputs / área de texto / lista</td></tr>
        <tr><td>Confirmación o error de obligatorios</td><td>Salida</td><td><span class="tag temp">Temporal</span></td><td>N/A (validación lógica)</td><td>Banner verde / borde rojo</td></tr>
      </tbody>
    </table>
    <p class="hint" style="margin-top:8px;font-size:.72rem;color:var(--muted)">Los campos marcados «confirmar en E2» no aparecen en la matriz original: verifica que existan exactamente así en tu E2 antes de entregar; si no existen, corrige E2 primero.</p>
  </details>

</div>
<script src="../js/app.js"></script>
</body>
</html>
