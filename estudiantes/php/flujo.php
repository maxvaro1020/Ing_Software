<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Flujo de navegación · Language Schoolar</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body data-page="flujo">
<div id="shell">

  <h1 class="page-title">Flujo de navegación</h1>
  <p class="page-sub">Flujo principal en azul, alternos en amarillo, errores en rojo y resultados exitosos en verde. Cada recuadro enlaza a su pantalla.</p>

  <div class="card">
    <h2>Flujo público: landing → curso → inscripción</h2>
    <div class="flow" style="margin-bottom:26px">
      <div class="row">
        <div class="node"><a href="index.php"><strong>Landing</strong></a><small>Idiomas</small></div>
        <div class="node"><a href="curso.php?i=ingles"><strong>Conocer el curso</strong></a><small>Descripción, niveles, horarios</small></div>
        <div class="node"><a href="inscribirme.php?i=ingles"><strong>Inscribirme</strong></a><small>Datos + grupo</small></div>
      </div>
      <div class="row">
        <div class="node okn"><strong>Solicitud enviada</strong><small>Folio y confirmación</small></div>
        <div class="node alt"><strong>Grupo lleno</strong><small>Lista de espera con posición</small></div>
        <div class="node err"><strong>Datos incompletos</strong><small>Campos en rojo y mensaje</small></div>
      </div>
    </div>
    <h2>Flujo interno del sistema</h2>
    <div class="flow">
      <div class="node"><strong>Landing + inicio de sesión</strong><small>Autenticación y rol</small></div>
      <div class="arrow">↓</div>
      <div class="node"><a href="panel.php"><strong>Menú principal</strong></a><small>Solo módulos permitidos por rol</small></div>
      <div class="arrow">↓</div>
      <div class="row">
        <div class="node"><a href="cu01-registrar-alumno.php"><strong>CU-01 Alumnos</strong></a><small>Registrar alumno</small></div>
        <div class="node"><a href="cu02-asignar-nivel.php"><strong>CU-02 Niveles</strong></a><small>Asignar nivel</small></div>
        <div class="node"><a href="cu03-inscripcion.php"><strong>CU-03 Inscripción</strong></a><small>Cupos / lista de espera</small></div>
        <div class="node"><a href="cu04-expediente.php"><strong>CU-04 Expediente</strong></a><small>Consulta unificada</small></div>
      </div>
      <div class="row">
        <div class="node"><a href="cu05-registrar-faq.php"><strong>CU-05 FAQ</strong></a><small>Registrar y categorizar</small></div>
        <div class="node"><a href="cu06-consultar-faq.php"><strong>CU-06 FAQ</strong></a><small>Consultar preguntas</small></div>
        <div class="node"><a href="cu07-validar-pago.php"><strong>CU-07 Pagos</strong></a><small>Validar / rechazar pago</small></div>
      </div>
      <div class="arrow">↓ resultados</div>
      <div class="row">
        <div class="node okn"><strong>Éxito</strong><small>Banner verde · matrícula, inscripción o pago validado</small></div>
        <div class="node alt"><strong>Alterno CU-03 (A1)</strong><small>Grupo lleno → agregar a lista de espera</small></div>
        <div class="node err"><strong>Errores CU-01 / CU-07 (A1, A2)</strong><small>Datos incompletos, error de guardado o motivo de rechazo faltante</small></div>
      </div>
      <div class="arrow">↺</div>
      <div class="node"><strong>Cancelar / Volver al menú</strong><small>Disponible antes de confirmar cambios</small></div>
    </div>
  </div>

</div>
<script src="../js/app.js"></script>
</body>
</html>
