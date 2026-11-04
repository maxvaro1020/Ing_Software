<?php
require_once '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_POST['id_usuario'];
    $id_curso = $_POST['id_curso'];

    try {
        $sql = "INSERT INTO inscripciones (id_usuario, id_curso, estado) VALUES (:id_usuario, :id_curso, 'pendiente')";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ':id_usuario' => $id_usuario,
            ':id_curso' => $id_curso
        ]);

        echo "<script>alert('Inscripción completada correctamente'); window.location.href='../views/cu03-inscripcion.php';</script>";
    } catch (PDOException $e) {
        echo "Error en la inscripción: " . $e->getMessage();
    }
}
?>
