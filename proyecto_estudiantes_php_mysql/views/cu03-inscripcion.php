<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CU03 - Inscripción</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Inscripción a Cursos</h2>
        <form action="../controllers/procesar_inscripcion.php" method="POST">
            <label>ID Alumno:</label>
            <input type="number" name="id_usuario" required>

            <label>ID Curso:</label>
            <input type="number" name="id_curso" required>

            <button type="submit">Inscribirse</button>
        </form>
    </div>
</body>
</html>
