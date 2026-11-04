<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CU05 - Registrar FAQ</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Registrar Nueva FAQ</h2>
        <form action="../controllers/procesar_faq.php" method="POST">
            <label>Categoría:</label>
            <input type="text" name="categoria" required>

            <label>Pregunta:</label>
            <textarea name="pregunta" required></textarea>

            <label>Respuesta:</label>
            <textarea name="respuesta" required></textarea>

            <button type="submit">Guardar FAQ</button>
        </form>
    </div>
</body>
</html>
