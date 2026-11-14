<?php
require_once __DIR__.'/config/database.php';
$pageTitle='Cursos'; $msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $a=$_POST['action'] ?? ''; $id=(int)($_POST['id'] ?? 0);
  if($a==='crear' && trim($_POST['nombre'])!==''){ $pdo->prepare("INSERT INTO cursos(nombre,descripcion,duracion_horas,lema,aprenderas,para_quien,foto_url) VALUES(?,?,?,?,?,?,?)")->execute([trim($_POST['nombre']),trim($_POST['descripcion']),(int)$_POST['horas']?:null,trim($_POST['lema']),trim($_POST['aprenderas']),trim($_POST['para_quien']),trim($_POST['foto_url'])]); $msg='Curso creado.'; }
  if($a==='estado'){ $pdo->prepare("UPDATE cursos SET estado=IF(estado='activo','inactivo','activo') WHERE id_curso=?")->execute([$id]); $msg='Estado actualizado.'; }
  if($a==='eliminar'){ $pdo->prepare("DELETE FROM cursos WHERE id_curso=?")->execute([$id]); $msg='Curso eliminado.'; }
}
$cursos=$pdo->query("SELECT c.*,(SELECT COUNT(*) FROM grupos g WHERE g.id_curso=c.id_curso) ng FROM cursos c ORDER BY c.nombre")->fetchAll();
require __DIR__.'/includes/header.php';
?>
<h1>Cursos</h1><p class="muted">Los cursos activos se muestran en la página principal con su descripción.</p>
<?php if($msg): ?><div class="alert"><?=$msg?></div><?php endif; ?>
<section class="card"><h2>Nuevo curso</h2><form method="post" class="grid"><input type="hidden" name="action" value="crear">
<div><label>Nombre *</label><input name="nombre" required></div><div><label>Duración (horas)</label><input type="number" name="horas" min="1"></div>
<div style="grid-column:1/-1"><label>Descripción</label><textarea name="descripcion" rows="3"></textarea></div><div><label>Lema</label><input name="lema"></div><div><label>URL de la foto</label><input name="foto_url"></div>
<div style="grid-column:1/-1"><label>Qué aprenderás (una línea por punto)</label><textarea name="aprenderas" rows="3"></textarea></div><div style="grid-column:1/-1"><label>¿Para quién es?</label><input name="para_quien"></div><div><button class="btn">Crear curso</button></div></form></section>
<section class="card"><div class="table-wrap"><table><thead><tr><th>Curso</th><th>Horas</th><th>Grupos</th><th>Estado</th><th></th></tr></thead><tbody>
<?php foreach($cursos as $c): ?><tr><td><b><?=htmlspecialchars($c['nombre'])?></b><br><small class="muted"><?=htmlspecialchars(mb_strimwidth((string)$c['descripcion'],0,90,'…'))?></small></td><td><?=$c['duracion_horas']?></td><td><?=$c['ng']?></td>
<td><span class="tag <?=$c['estado']==='activo'?'':'err'?>"><?=$c['estado']?></span></td>
<td><div class="actions"><form method="post"><input type="hidden" name="action" value="estado"><input type="hidden" name="id" value="<?=$c['id_curso']?>"><button class="btn secondary small">Activar/Desactivar</button></form>
<form method="post" onsubmit="return confirm('¿Eliminar curso y sus inscripciones?')"><input type="hidden" name="action" value="eliminar"><input type="hidden" name="id" value="<?=$c['id_curso']?>"><button class="btn danger small">Eliminar</button></form></div></td></tr><?php endforeach; ?>
</tbody></table></div></section>
<?php require __DIR__.'/includes/footer.php'; ?>
