<?php
require_once '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_inscripcion = $_POST['id_inscripcion'];
    $monto = $_POST['monto'];
    
    // Tratamiento básico de archivo subido
    $comprobante_url = "";
    if (isset($_FILES['comprobante']) && $_FILES['comprobante']['error'] == 0) {
        $nombreArchivo = time() . "_" . $_FILES['comprobante']['name'];
        move_uploaded_file($_FILES['comprobante']['tmp_name'], "../uploads/" . $nombreArchivo);
        $comprobante_url = "uploads/" . $nombreArchivo;
    }

    try {
        $sql = "INSERT INTO pagos (id_inscripcion, monto, comprobante_url, estado_pago) 
                VALUES (:id_inscripcion, :monto, :comprobante_url, 'pendiente')";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ':id_inscripcion' => $id_inscripcion,
            ':monto' => $monto,
            ':comprobante_url' => $comprobante_url
        ]);

        echo "<script>alert('Pago enviado a validación'); window.location.href='../views/cu07-validar-pago.php';</script>";
    } catch (PDOException $e) {
        echo "Error al registrar el pago: " . $e->getMessage();
    }
}
?>
