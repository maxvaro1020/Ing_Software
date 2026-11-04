<?php
require_once '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $telefono = $_POST['telefono'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];

    try {
        $sql = "INSERT INTO usuarios (nombre, apellido, email, password, telefono, fecha_nacimiento) 
                VALUES (:nombre, :apellido, :email, :password, :telefono, :fecha_nacimiento)";
        
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':email' => $email,
            ':password' => $password,
            ':telefono' => $telefono,
            ':fecha_nacimiento' => $fecha_nacimiento
        ]);

        echo "<script>alert('Alumno registrado exitosamente'); window.location.href='../views/cu01-registrar-alumno.php';</script>";
    } catch (PDOException $e) {
        echo "Error al registrar alumno: " . $e->getMessage();
    }
}
?>
