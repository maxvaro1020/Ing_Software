<?php
define('PUBLICO',1);
require_once __DIR__.'/config/database.php';
if(esAdmin()){ header('Location: panel.php'); exit; }
$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(strcasecmp(trim($_POST['email'] ?? ''),ADMIN_EMAIL)===0 && ($_POST['password'] ?? '')===ADMIN_PASS){ $_SESSION['admin']=true; header('Location: panel.php'); exit; }
  $err='Correo o contraseña de administrador incorrectos.';
}
$titulo='Acceso administración';
require __DIR__.'/includes/public_header.php';
?>
<main class="enroll"><div class="wrap" style="max-width:460px">
  <div class="eyebrow">Administración</div><h1>Acceso del personal.</h1>
  <p class="lead">Entra para gestionar alumnos, cursos, grupos, inscripciones, pagos y preguntas frecuentes.</p>
  <div class="form-card"><form method="post">
    <div class="ff"><label>Correo de administrador</label><input type="email" name="email" placeholder="admin@languageschoolar.com" required></div>
    <div class="ff"><label>Contraseña</label><input type="password" name="password" required></div>
    <?php if($err): ?><div class="notice err show"><?= $err ?></div><?php endif; ?>
    <button class="btn-green" type="submit" style="width:100%">Entrar a administración ↗</button>
  </form></div>
  <p style="text-align:center;margin-top:14px"><a href="index.php">← Volver al sitio principal</a></p>
</div></main>
<?php require __DIR__.'/includes/public_footer.php'; ?>
