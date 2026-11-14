<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CU-01 · Registrar alumno</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body data-page="cu01">
<div id="shell">

  <h1 class="page-title">CU-01 · Registrar alumno</h1>
  <p class="page-sub">Actor: Servicios Escolares / Recepción · RF-01 · Flujo principal: pasos 1-7 · Alternos: A1 datos incompletos, A2 error de persistencia</p>

  <div class="legend">
    Tipo de dato:
    <span><span class="tag persist">Persistente</span> se guarda en BD</span>
    <span><span class="tag query">Consultado</span> lo lee el sistema</span>
    <span><span class="tag calc">Calculado</span> lo genera el sistema</span>
    <span><span class="tag temp">Temporal</span> solo en pantalla</span>
  </div>

  <div class="state-bar">
    Simular estado:
    <button data-state="normal" class="on">Normal</button>
    <button data-state="error">A1 · Datos incompletos</button>
    <button data-state="persist">A2 · Error de persistencia</button>
    <button data-state="ok">Éxito</button>
  </div>

  <form id="formAlumno" class="card" novalidate>
    <h2>Datos del alumno</h2>
    <div class="grid-2">
      <div class="field" id="f-nombre">
        <label for="nombre">Nombre <span class="req">*</span> <span class="tag persist">ALUMNO.nombre</span></label>
        <input type="text" id="nombre" placeholder="Nombre del alumno">
        <span class="error-msg">Campo obligatorio no completado.</span>
      </div>
      <div class="field" id="f-apellidos">
        <label for="apellidos">Apellidos <span class="req">*</span> <span class="tag persist">ALUMNO.apellidos</span></label>
        <input type="text" id="apellidos" placeholder="Apellidos del alumno">
        <span class="error-msg">Campo obligatorio no completado.</span>
      </div>
      <div class="field" id="f-correo">
        <label for="correo">Correo <span class="req">*</span> <span class="tag persist">ALUMNO.correo</span></label>
        <input type="email" id="correo" placeholder="correo@ejemplo.com">
        <span class="error-msg">Campo obligatorio no completado.</span>
      </div>
      <div class="field" id="f-telefono">
        <label for="telefono">Teléfono <span class="req">*</span> <span class="tag persist">ALUMNO.telefono</span></label>
        <input type="tel" id="telefono" placeholder="Número de contacto">
        <span class="error-msg">Campo obligatorio no completado.</span>
      </div>
    </div>

    <div class="field" style="max-width:260px">
      <label for="matricula">Matrícula generada <span class="tag calc">Calculado · salida</span></label>
      <input type="text" id="matricula" readonly placeholder="Se genera al registrar">
      <span class="hint">Salida del sistema (ALUMNO.id_alumno, PK autogenerada). No se captura.</span>
    </div>

    <div class="actions">
      <button class="btn" type="submit">Registrar</button>
      <button class="btn secondary" type="button" id="btnLimpiar">Cancelar</button>
      <a class="btn secondary" href="panel.php">Volver al menú</a>
    </div>

    <div id="out" class="alert" role="status"></div>
  </form>

  <details class="trace">
    <summary>Ver trazabilidad CU → RF → paso → dato → tabla/campo → control</summary>
    <table class="data">
      <thead><tr><th>Paso</th><th>Dato</th><th>E/S</th><th>Tipo</th><th>Tabla / campo</th><th>Control</th></tr></thead>
      <tbody>
        <tr><td>3</td><td>Nombre, Apellidos, Correo, Teléfono</td><td>Entrada</td><td><span class="tag persist">Persistente</span></td><td>ALUMNO.nombre, apellidos, correo, telefono</td><td>Inputs de texto</td></tr>
        <tr><td>4</td><td>Acción registrar</td><td>Entrada</td><td>—</td><td>—</td><td>Botón «Registrar»</td></tr>
        <tr><td>5</td><td>Validación de obligatorios</td><td>Salida</td><td><span class="tag temp">Temporal</span></td><td>N/A (validación lógica)</td><td>Borde rojo + mensaje inline</td></tr>
        <tr><td>6-7</td><td>Matrícula / ID asignado</td><td>Salida</td><td><span class="tag calc">Calculado</span></td><td>ALUMNO.id_alumno (PK)</td><td>Campo de solo lectura + banner verde</td></tr>
        <tr><td>A2</td><td>Error de guardado</td><td>Salida</td><td><span class="tag temp">Temporal</span></td><td>N/A</td><td>Banner rojo</td></tr>
      </tbody>
    </table>
  </details>

</div>
<script src="../js/app.js"></script>
</body>
</html>
