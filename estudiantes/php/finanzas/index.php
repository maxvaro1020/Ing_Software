<?php
define('PUBLICO',1);
require_once __DIR__.'/../config/database.php';
if(esFinanzas()){ header('Location: panel.php'); exit; }
$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(strcasecmp(trim($_POST['email'] ?? ''),FIN_EMAIL)===0 && ($_POST['password'] ?? '')===FIN_PASS){ $_SESSION['finanzas']=true; header('Location: panel.php'); exit; }
  $err='Correo o contraseña de Servicios Financieros incorrectos.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Acceso Servicios Financieros · Language Schoolar</title>
<link rel="stylesheet" href="../../css/admin.css">
</head>
<body>
<header class="topbar"><a class="brand" href="../index.php">Language Schoolar</a><div class="user"><a class="out" href="../index.php">← Sitio principal</a></div></header>
<main class="content" style="max-width:460px;margin:40px auto">
  <h1>Servicios Financieros</h1>
  <p class="muted">Acceso exclusivo para validar pagos y comprobantes.</p>
  <section class="card"><form method="post">
    <label>Correo</label><input type="email" name="email" placeholder="finanzas@languageschoolar.com" required>
    <label>Contraseña</label><input type="password" name="password" required>
    <?php if($err): ?><div class="alert error"><?= htmlspecialchars($err) ?></div><?php endif; ?>
    <p><button class="btn" type="submit" style="width:100%">Entrar</button></p>
  </form></section>
</main>
</body>
</html>
