<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CU07 - Validar Pago</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Registrar Pago</h2>
        <form action="../controllers/procesar_pago.php" method="POST" enctype="multipart/form-data">
            <label>ID Inscripción:</label>
            <input type="number" name="id_inscripcion" required>

            <label>Monto:</label>
            <input type="number" step="0.01" name="monto" required>

            <label>Comprobante:</label>
            <input type="file" name="comprobante">

            <button type="submit">Enviar Pago</button>
        </form>
    </div>
</body>
</html>
