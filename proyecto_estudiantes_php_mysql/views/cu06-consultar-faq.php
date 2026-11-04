<?php
require_once '../includes/conexion.php';
$stmt = $conexion->query("SELECT * FROM faqs ORDER BY fecha_creacion DESC");
$faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CU06 - Consultar FAQs</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Preguntas Frecuentes</h2>
        <div class="faq-list">
            <?php foreach ($faqs as $faq): ?>
                <div class="faq-item">
                    <h3>[<?php echo htmlspecialchars($faq['categoria']); ?>] <?php echo htmlspecialchars($faq['pregunta']); ?></h3>
                    <p><?php echo htmlspecialchars($faq['respuesta']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
