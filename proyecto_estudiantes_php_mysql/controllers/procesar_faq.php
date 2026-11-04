<?php
require_once '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoria = trim($_POST['categoria']);
    $pregunta = trim($_POST['pregunta']);
    $respuesta = trim($_POST['respuesta']);

    try {
        $sql = "INSERT INTO faqs (categoria, pregunta, respuesta) VALUES (:categoria, :pregunta, :respuesta)";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ':categoria' => $categoria,
            ':pregunta' => $pregunta,
            ':respuesta' => $respuesta
        ]);

        echo "<script>alert('FAQ registrada con éxito'); window.location.href='../views/cu05-registrar-faq.php';</script>";
    } catch (PDOException $e) {
        echo "Error al guardar FAQ: " . $e->getMessage();
    }
}
?>
