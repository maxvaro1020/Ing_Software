<?php require_once __DIR__.'/../config/sesion.php'; $al=alumnoActual(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($titulo ?? 'Language Schoolar') ?> · Language Schoolar</title>
<link rel="stylesheet" href="../css/landing.css">
</head>
<body>
<header class="site"><div class="wrap">
  <a class="logo" href="index.php"><span class="mark">▭</span> Language Schoolar</a>
  <nav class="main"><a href="index.php#idiomas">Idiomas</a><a href="index.php#metodo">Cómo aprendemos</a><a href="index.php#faq">Preguntas frecuentes</a></nav>
  <?php if(esAdmin()): ?><a class="account-btn" href="panel.php" style="text-decoration:none">⚙ Administración</a>
  <?php elseif($al): ?><a class="account-btn" href="mi-cuenta.php" style="text-decoration:none">☺ <?= htmlspecialchars($al['nombre']) ?></a>
  <?php elseif(!empty($esIndex)): ?><button class="account-btn" id="accountBtn">☺ Mi cuenta ⌄</button>
  <?php else: ?><a class="account-btn" href="index.php?login=1" style="text-decoration:none">☺ Mi cuenta</a><?php endif; ?>
  <?php if(!esAdmin()): ?><a class="account-btn" href="admin.php" style="text-decoration:none;margin-left:8px">⚙ Admin</a><?php endif; ?>
  <?php if(esAdmin() || $al): ?><a class="account-btn" href="logout.php" style="text-decoration:none;margin-left:8px">Cerrar sesión</a><?php endif; ?>
</div></header>
