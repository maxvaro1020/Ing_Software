<?php
require_once __DIR__ . '/../config/database.php';
$id=(int)($_GET['id']??0);
$st=$pdo->prepare("SELECT * FROM listas_alumnos WHERE id_lista=?");$st->execute([$id]);$lista=$st->fetch();
if(!$lista){http_response_code(404);exit('Lista no encontrada.');}
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 if(($_POST['action']??'')==='agregar'){
  $st=$pdo->prepare("INSERT IGNORE INTO lista_alumnos_detalle(id_lista,id_usuario) VALUES(?,?)");$st->execute([$id,(int)$_POST['id_usuario']]);$msg='Alumno agregado a la lista.';
 }elseif(($_POST['action']??'')==='quitar'){
  $st=$pdo->prepare("DELETE FROM lista_alumnos_detalle WHERE id_lista=? AND id_usuario=?");$st->execute([$id,(int)$_POST['id_usuario']]);$msg='Alumno retirado de la lista.';
 }
}
$st=$pdo->prepare("SELECT u.id_usuario,u.nombre,u.apellido,u.email FROM usuarios u WHERE NOT EXISTS(SELECT 1 FROM lista_alumnos_detalle d WHERE d.id_lista=? AND d.id_usuario=u.id_usuario) ORDER BY u.apellido,u.nombre");$st->execute([$id]);$disponibles=$st->fetchAll();
$st=$pdo->prepare("SELECT u.id_usuario,u.nombre,u.apellido,u.email FROM usuarios u JOIN lista_alumnos_detalle d ON d.id_usuario=u.id_usuario WHERE d.id_lista=? ORDER BY u.apellido,u.nombre");$st->execute([$id]);$alumnos=$st->fetchAll();
$pageTitle='Administrar lista';require __DIR__.'/../includes/header.php';
?>
<h1><?=htmlspecialchars($lista['nombre_lista'])?></h1><p class="muted">Puedes añadir o quitar alumnos sin borrar sus cuentas del sistema.</p>
<?php if($msg): ?><div class="alert"><?=htmlspecialchars($msg)?></div><?php endif; ?>
<section class="card"><h2>Agregar alumno a la lista</h2><form method="post" class="grid"><input type="hidden" name="action" value="agregar"><div><label>Alumno disponible</label><select name="id_usuario" required><option value="">Seleccionar...</option><?php foreach($disponibles as $u): ?><option value="<?=$u['id_usuario']?>"><?=htmlspecialchars($u['apellido'].', '.$u['nombre'].' · '.$u['email'])?></option><?php endforeach; ?></select></div><div style="align-self:end"><button class="btn">Agregar alumno</button></div></form></section>
<section class="card"><h2>Integrantes (<?=count($alumnos)?>)</h2>
<?php if(!$alumnos): ?><p class="muted">La lista está vacía.</p><?php else: ?><div class="table-wrap"><table><thead><tr><th>#</th><th>Alumno</th><th>Correo</th><th>Acción</th></tr></thead><tbody><?php foreach($alumnos as $i=>$u): ?><tr><td><?=$i+1?></td><td><?=htmlspecialchars($u['nombre'].' '.$u['apellido'])?></td><td><?=htmlspecialchars($u['email'])?></td><td><form method="post" onsubmit="return confirm('¿Quitar este alumno de la lista?');"><input type="hidden" name="action" value="quitar"><input type="hidden" name="id_usuario" value="<?=$u['id_usuario']?>"><button class="btn danger small">Quitar</button></form></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
<div class="actions"><a class="btn secondary" href="index.php">Volver al generador</a><button class="btn" onclick="window.print()">Imprimir lista</button></div>
<?php require __DIR__.'/../includes/footer.php'; ?>
