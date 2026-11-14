<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Trazabilidad · Language Schoolar</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body data-page="trazabilidad">
<div id="shell">

  <h1 class="page-title">Trazabilidad CU – RF – Datos – BD – Interfaz</h1>
  <p class="page-sub">Cadena completa: CU → RF → paso → dato → tabla/campo → elemento de interfaz → resultado. La columna «Tabla/campo» es trazabilidad lógica: la interfaz no accede a la BD, pasa por la capa de servicios.</p>

  <div class="legend">
    <span><span class="tag persist">Persistente</span> se guarda en BD</span>
    <span><span class="tag query">Consultado</span> se lee de BD</span>
    <span><span class="tag calc">Calculado</span> lo genera el sistema</span>
    <span><span class="tag temp">Temporal</span> solo en pantalla</span>
  </div>

  <div class="card">
    <h2>Pantallas críticas (CU-01, CU-03, CU-07)</h2>
    <table class="data">
      <thead><tr><th>CU / RF</th><th>Paso</th><th>Dato</th><th>E/S</th><th>Tipo</th><th>Tabla / campo</th><th>Control</th><th>Resultado</th></tr></thead>
      <tbody>
        <tr><td>CU-01 / RF-01</td><td>3</td><td>Nombre, Apellidos, Correo, Teléfono</td><td>Entrada</td><td><span class="tag persist">Persistente</span></td><td>ALUMNO.nombre, apellidos, correo, telefono</td><td>Inputs + botón Registrar</td><td>Formulario completo</td></tr>
        <tr><td>CU-01 / RF-01</td><td>5-7</td><td>Matrícula / ID</td><td>Salida</td><td><span class="tag calc">Calculado</span></td><td>ALUMNO.id_alumno (PK)</td><td>Campo solo lectura + banner verde</td><td>Alumno registrado</td></tr>
        <tr><td>CU-01 / RF-01</td><td>A1</td><td>Campos omitidos</td><td>Salida</td><td><span class="tag temp">Temporal</span></td><td>N/A</td><td>Borde rojo + mensaje inline</td><td>Campo obligatorio no completado</td></tr>
        <tr><td>CU-03 / RF-03, RF-05</td><td>1-3</td><td>Alumno, grupo/horario/modalidad</td><td>Entrada</td><td><span class="tag query">Consultado</span></td><td>ALUMNO.id_alumno, GRUPO.id_grupo</td><td>Listas desplegables</td><td>Selección válida</td></tr>
        <tr><td>CU-03 / RF-03</td><td>4</td><td>Cupos disponibles</td><td>Salida</td><td><span class="tag query">Consultado</span></td><td>GRUPO.cupo_disponible</td><td>Badge + barra</td><td>«8 cupos disponibles»</td></tr>
        <tr><td>CU-03 / RF-05</td><td>5-6</td><td>Inscripción</td><td>Salida</td><td><span class="tag persist">Persistente</span></td><td>INSCRIPCION.id_inscripcion</td><td>Botón Inscribir + modal</td><td>Inscripción realizada</td></tr>
        <tr><td>CU-03 / RF-04</td><td>A1</td><td>Grupo saturado, posición</td><td>Salida</td><td><span class="tag persist">Persistente</span></td><td>LISTA_ESPERA.id_espera, posicion</td><td>Banner amarillo + botón lista de espera</td><td>Agregado a lista (#N)</td></tr>
        <tr><td>CU-07 / RF-10</td><td>1-2</td><td>Pago y comprobante</td><td>Salida</td><td><span class="tag query">Consultado</span></td><td>PAGO.id_pago, comprobante_url</td><td>Lista + visor</td><td>Comprobante desplegado</td></tr>
        <tr><td>CU-07 / RF-10, RF-11</td><td>3-5</td><td>Estado seleccionado</td><td>Entrada</td><td><span class="tag persist">Persistente</span></td><td>PAGO.estado; MATRICULA.estatus (CU-11)</td><td>Opciones + botón Confirmar + modal</td><td>Pago validado / matrícula ACTIVO</td></tr>
        <tr><td>CU-07 / RF-10</td><td>A1</td><td>Motivo de rechazo</td><td>Entrada</td><td><span class="tag persist">Persistente</span></td><td>PAGO.estado, PAGO.observaciones</td><td>Área de texto + banner rojo</td><td>Rechazado, matrícula PENDIENTE</td></tr>
      </tbody>
    </table>
  </div>

  <div class="card">
    <h2>Puntos que se deben verificar contra E2 definitivo</h2>
    <table class="data">
      <thead><tr><th>Elemento</th><th>Verificación</th></tr></thead>
      <tbody>
        <tr><td>LISTA_ESPERA (id_espera, posicion)</td><td>Debe existir exactamente con esos nombres. Si no, corregir E2 antes de la interfaz.</td></tr>
        <tr><td>MATRICULA.estatus</td><td>Debe existir. Además, aclarar si la «matrícula AL-XXXX» es ALUMNO.id_alumno o un campo propio.</td></tr>
        <tr><td>Dirección (entrada de CU-01 en la matriz)</td><td>No tenía tabla/campo ni aparecía en el wireframe. Se quitó de la interfaz. Agregarla a ALUMNO en E2 si se necesita.</td></tr>
        <tr><td>Tablas de nivel y de preguntas frecuentes</td><td>No aparecen en la matriz original; confirmar nombres en E2 (CU-02, CU-05, CU-06).</td></tr>
      </tbody>
    </table>
  </div>

  <div class="card">
    <h2>Propuesta de IA rechazada / corregida</h2>
    <table class="data">
      <thead><tr><th>Propuesta de la IA</th><th>Decisión</th><th>Justificación</th></tr></thead>
      <tbody>
        <tr><td>Campo «Dirección» en CU-01</td><td><span class="badge err">Rechazada</span></td><td>No tenía tabla/campo en E2 ni aparecía en el wireframe aprobado.</td></tr>
        <tr><td>Menú «Grupos» separado de «Inscripciones»</td><td><span class="badge err">Rechazada</span></td><td>No corresponde a un CU; el grupo se elige dentro de CU-03.</td></tr>
        <tr><td>Texto de ejemplo en el banner verde por defecto (Salida / estado)</td><td><span class="badge warn">Corregida</span></td><td>Los mensajes de error o advertencia no pueden verse en verde: se asignó verde, amarillo y rojo por tipo de resultado.</td></tr>
      </tbody>
    </table>
  </div>

  <div class="card">
    <h2>Conclusión</h2>
    <p style="font-size:.9rem;line-height:1.6">La trazabilidad se mantuvo partiendo de cada caso de uso y sus RF. De cada paso del flujo se derivó qué necesita hacer o conocer el usuario, y de ahí los datos de entrada y salida. Cada dato persistente se relacionó con su tabla y campo de E2 (por ejemplo ALUMNO.nombre, GRUPO.cupo_disponible o PAGO.estado). Los datos consultados, calculados o temporales se etiquetaron aparte, como la matrícula generada o la posición en lista de espera, para no introducir estructuras nuevas desde la interfaz. Cada dato se tradujo en un control concreto: inputs para capturas, badge para cupos, modal para confirmaciones y banners de color para resultados y errores. Las propuestas de la IA se contrastaron con la matriz y se descartaron las que no tenían sustento. Así se puede defender la cadena completa: CU → RF → paso → dato → tabla/campo → elemento de interfaz → resultado.</p>
  </div>

</div>
<script src="../js/app.js"></script>
</body>
</html>
