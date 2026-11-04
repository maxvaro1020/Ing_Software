<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CU01 - Registrar Alumno</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Registrar Nuevo Alumno</h2>
        <form action="../controllers/procesar_registro.php" method="POST">
            <label>Nombre:</label>
            <input type="text" name="nombre" required>

            <label>Apellido:</label>
            <input type="text" name="apellido" required>

            <label>Correo Electrónico:</label>
            <input type="email" name="email" required>

            <label>Contraseña:</label>
            <input type="password" name="password" required>

            <label>Teléfono:</label>
            <input type="tel" name="telefono">

            <label>Fecha de Nacimiento:</label>
            <input type="date" name="fecha_nacimiento">

            <button type="submit">Registrar Alumno</button>
        </form>
    </div>
</body>
</html>
