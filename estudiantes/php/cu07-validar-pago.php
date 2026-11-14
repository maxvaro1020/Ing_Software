<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CU-07 · Validar pago y estado de cuenta</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body data-page="cu07">
<div id="shell">

  <h1 class="page-title">CU-07 · Módulo de validación de pagos</h1>
  <p class="page-sub">Actor: Caja / Servicios Financieros · RF-10, RF-11 · Alterno A1: comprobante rechazado → motivo obligatorio, matrícula PENDIENTE</p>

  <div class="legend">
    Tipo de dato:
    <span><span class="tag persist">Persistente</span></span>
    <span><span class="tag query">Consultado</span></span>
    <span><span class="tag calc">Calculado</span></span>
    <span><span class="tag temp">Temporal</span></span>
  </div>

  <form id="formPago" novalidate>
    <div class="two-col">
      <div>
        <h2 style="font-size:.9rem;margin-bottom:8px">Pagos pendientes <span class="tag query">PAGO.id_pago</span></h2>
        <div class="list-box" id="listaPagos"></div>
      </div>

      <div class="card" style="margin:0">
        <h2>Comprobante adjunto <span class="tag query">PAGO.comprobante_url</span></h2>
        <div class="viewer" id="visor">Selecciona un pago pendiente para ver su comprobante.</div>

        <div class="field" id="f-estado" style="margin-top:16px">
          <label>Estado del pago <span class="req">*</span> <span class="tag persist">PAGO.estado</span></label>
          <div class="radio-row">
            <label><input type="radio" name="estado" value="Validado"> Validado</label>
            <label><input type="radio" name="estado" value="Rechazado"> Rechazado</label>
          </div>
          <span class="error-msg">Selecciona Validado o Rechazado.</span>
        </div>

        <div class="field" id="f-obs">
          <label for="obs" id="lblObs">Observaciones <span class="tag persist">PAGO.observaciones</span></label>
          <textarea id="obs" placeholder="Motivo obligatorio si se rechaza"></textarea>
          <span class="error-msg">El motivo es obligatorio al rechazar.</span>
        </div>

        <div class="actions">
          <button class="btn" type="submit">Confirmar decisión</button>
          <a class="btn secondary" href="panel.php">Cancelar</a>
        </div>
        <div id="out" class="alert" role="status"></div>
        <p class="hint" style="font-size:.72rem;color:var(--muted)">Al validar, el sistema ejecuta CU-11: <span class="tag persist">MATRICULA.estatus</span> pasa a ACTIVO (salida calculada por el sistema).</p>
      </div>
    </div>
  </form>

  <details class="trace">
    <summary>Ver trazabilidad CU → RF → paso → dato → tabla/campo → control</summary>
    <table class="data">
      <thead><tr><th>Paso</th><th>Dato</th><th>E/S</th><th>Tipo</th><th>Tabla / campo</th><th>Control</th></tr></thead>
      <tbody>
        <tr><td>1-2</td><td>Pago pendiente (ID/folio)</td><td>Entrada</td><td><span class="tag query">Consultado</span></td><td>PAGO.id_pago</td><td>Lista de pagos pendientes</td></tr>
        <tr><td>2</td><td>Comprobante</td><td>Salida</td><td><span class="tag query">Consultado</span></td><td>PAGO.comprobante_url</td><td>Visor de comprobante</td></tr>
        <tr><td>3-4</td><td>Estado seleccionado</td><td>Entrada</td><td><span class="tag persist">Persistente</span></td><td>PAGO.estado</td><td>Opciones Validado / Rechazado + botón «Confirmar decisión» + modal</td></tr>
        <tr><td>5</td><td>Estatus de matrícula</td><td>Salida</td><td><span class="tag calc">Calculado</span></td><td>MATRICULA.estatus (CU-11)</td><td>Banner verde</td></tr>
        <tr><td>A1</td><td>Motivo de rechazo</td><td>Entrada</td><td><span class="tag persist">Persistente</span></td><td>PAGO.observaciones</td><td>Área de texto obligatoria + banner rojo</td></tr>
      </tbody>
    </table>
  </details>

  <!-- Modal de confirmación -->
  <div class="modal-bg" id="modalPago">
    <div class="modal">
      <h3>Confirmar decisión</h3>
      <p id="modalTxt"></p>
      <div class="actions">
        <button class="btn" id="okPago" type="button">Confirmar</button>
        <button class="btn secondary" id="cancelPago" type="button">Cancelar</button>
      </div>
    </div>
  </div>

</div>
<script src="../js/app.js"></script>
</body>
</html>
