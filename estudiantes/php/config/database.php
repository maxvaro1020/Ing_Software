<?php
// Configuración de conexión a la misma base de datos sistema_estudiantes.
$host = 'localhost';
$db   = 'sistema_estudiantes';
$user = 'root';
$pass = ''; // Cambia esta contraseña si tu MySQL utiliza una.
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    exit('No se pudo conectar con la base de datos. Revisa php/config/database.php y que MySQL esté iniciado. Detalle: ' . htmlspecialchars($e->getMessage()));
}

require_once __DIR__.'/migrar.php';
asegurarEsquema($pdo);

// Las páginas públicas definen PUBLICO; las de Servicios Financieros definen FINANZAS;
// todo lo demás exige sesión de administrador. Los roles están separados: el administrador
// no entra a finanzas/ y Servicios Financieros no entra a las pantallas de administración.
require_once __DIR__.'/sesion.php';
if (defined('FINANZAS')) requireFinanzas();
elseif (!defined('PUBLICO')) requireAdmin();
