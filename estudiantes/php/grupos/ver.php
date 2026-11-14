<?php
require_once __DIR__.'/../config/database.php';
$id=(int)($_GET['id'] ?? 0);
$st=$pdo->prepare("SELECT * FROM grupos WHERE id_grupo=?");$st->execute([$id]);$grupo=$st->fetch();
if(!$grupo){http_response_code(404);exit('Grupo no encontrado.');}
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 if(($_POST['action']??'')==='agregar'){
  $uid=(int)($_POST['id_usuario']??0);
  $q=$pdo->prepare("INSERT IGNORE INTO grupo_alumnos(id_grupo,id_usuario) VALUES(?,?)");$q->execute([$id,$uid]);$msg='Alumno agregado al grupo.';
 }elseif(($_POST['action']??'')==='quitar'){
  $q=$pdo->prepare("DELETE FROM grupo_alumnos WHERE id_grupo=? AND id_usuario=?");$q->execute([$id,(int)$_POST['id_usuario']]);$msg='Alumno retirado del grupo.';
 }
}
$st=$pdo->prepare("SELECT u.id_usuario,u.nombre,u.apellido,u.email FROM usuarios u WHERE NOT EXISTS(SELECT 1 FROM grupo_alumnos ga WHERE ga.id_grupo=? AND ga.id_usuario=u.id_usuario) ORDER BY u.apellido,u.nombre");
$st->execute([$id]);$disponibles=$st->fetchAll();
$st=$pdo->prepare("SELECT u.id_usuario,u.nombre,u.apellido,u.email FROM usuarios u JOIN grupo_alumnos ga ON ga.id_usuario=u.id_usuario WHERE ga.id_grupo=? ORDER BY u.apellido,u.nombre");
$st->execute([$id]);$alumnos=$st->fetchAll();
$pageTitle='Administrar alumnos del grupo';require __DIR__.'/../includes/header.php';
?>
<h1><?=htmlspecialchars($grupo['nombre_grupo'])?></h1><p class="muted">Agrega alumnos existentes o retíralos de este grupo. No se elimina su registro general.</p>
<?php if($msg): ?><div class="alert"><?=htmlspecialchars($msg)?></div><?php endif; ?>
<section class="card"><h2>Agregar alumno</h2>
<form method="post" class="grid"><input type="hidden" name="action" value="agregar"><div><label>Selecciona un alumno</label><select name="id_usuario" required><option value="">Seleccionar...</option><?php foreach($disponibles as $u): ?><option value="<?=$u['id_usuario']?>"><?=htmlspecialchars($u['apellido'].', '.$u['nombre'].' · '.$u['email'])?></option><?php endforeach; ?></select></div><div style="align-self:end"><button class="btn">Agregar al grupo</button></div></form>
</section>
<section class="card"><h2>Alumnos del grupo (<?=count($alumnos)?>)</h2>
<?php if(!$alumnos): ?><p class="muted">Este grupo todavía no tiene alumnos.</p><?php else: ?><div class="table-wrap"><table><thead><tr><th>Nombre</th><th>Correo</th><th>Acción</th></tr></thead><tbody><?php foreach($alumnos as $u): ?><tr><td><?=htmlspecialchars($u['nombre'].' '.$u['apellido'])?></td><td><?=htmlspecialchars($u['email'])?></td><td><form method="post" onsubmit="return confirm('¿Retirar a este alumno del grupo?');"><input type="hidden" name="action" value="quitar"><input type="hidden" name="id_usuario" value="<?=$u['id_usuario']?>"><button class="btn danger small">Quitar</button></form></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
<a class="btn secondary" href="index.php">Volver a grupos</a>
<?php require __DIR__.'/../includes/footer.php'; ?>
