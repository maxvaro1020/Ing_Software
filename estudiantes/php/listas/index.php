<?php
require_once __DIR__ . '/../config/database.php';
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $action=$_POST['action']??'';
 if($action==='generar'){
  $nombre=trim($_POST['nombre_lista']??'');$gid=(int)($_POST['id_grupo']??0);
  if($nombre!==''){
   $pdo->beginTransaction();
   try{
    $st=$pdo->prepare("INSERT INTO listas_alumnos(nombre_lista,descripcion,id_grupo) VALUES(?,?,?)");
    $st->execute([$nombre,trim($_POST['descripcion']??''),$gid ?: null]);$lid=(int)$pdo->lastInsertId();
    if($gid){$st=$pdo->prepare("INSERT IGNORE INTO lista_alumnos_detalle(id_lista,id_usuario) SELECT ?,id_usuario FROM grupo_alumnos WHERE id_grupo=?");$st->execute([$lid,$gid]);}
    $pdo->commit();$msg='Lista generada correctamente'.($gid?' con los alumnos del grupo seleccionado.':'.');
   }catch(Throwable $e){$pdo->rollBack();$msg='No se pudo generar la lista: '.$e->getMessage();}
  }else $msg='Escribe el nombre de la lista.';
 }elseif($action==='eliminar'){
  $st=$pdo->prepare("DELETE FROM listas_alumnos WHERE id_lista=?");$st->execute([(int)$_POST['id_lista']]);$msg='Lista eliminada.';
 }
}
$grupos=$pdo->query("SELECT id_grupo,nombre_grupo FROM grupos WHERE estado='activo' ORDER BY nombre_grupo")->fetchAll();
$listas=$pdo->query("SELECT l.*,g.nombre_grupo,COUNT(d.id_usuario) total FROM listas_alumnos l LEFT JOIN grupos g ON g.id_grupo=l.id_grupo LEFT JOIN lista_alumnos_detalle d ON d.id_lista=l.id_lista GROUP BY l.id_lista ORDER BY l.fecha_creacion DESC")->fetchAll();
$pageTitle='Generador de listas';require __DIR__.'/../includes/header.php';
?>
<h1>Generador de listas</h1><p class="muted">Crea una lista desde cero o genera automáticamente una con los alumnos de un grupo. Después podrás agregar o quitar integrantes.</p>
<?php if($msg): ?><div class="alert"><?=htmlspecialchars($msg)?></div><?php endif; ?>
<section class="card"><h2>Generar nueva lista</h2>
<form method="post" class="grid"><input type="hidden" name="action" value="generar">
<div><label>Nombre de la lista *</label><input name="nombre_lista" required placeholder="Ej. Lista de asistencia - Inglés A1"></div>
<div><label>Tomar alumnos de un grupo</label><select name="id_grupo"><option value="0">Lista vacía</option><?php foreach($grupos as $g): ?><option value="<?=$g['id_grupo']?>"><?=htmlspecialchars($g['nombre_grupo'])?></option><?php endforeach; ?></select></div>
<div><label>Descripción</label><input name="descripcion" placeholder="Descripción opcional"></div>
<div style="align-self:end"><button class="btn">Generar lista</button></div>
</form></section>
<section class="card"><h2>Listas creadas</h2>
<?php if(!$listas): ?><p class="muted">Todavía no has generado listas.</p><?php else: ?><div class="table-wrap"><table><thead><tr><th>Lista</th><th>Grupo origen</th><th>Alumnos</th><th>Fecha</th><th>Acciones</th></tr></thead><tbody>
<?php foreach($listas as $l): ?><tr><td><b><?=htmlspecialchars($l['nombre_lista'])?></b><br><span class="muted"><?=htmlspecialchars($l['descripcion']??'')?></span></td><td><?=htmlspecialchars($l['nombre_grupo']??'Lista manual')?></td><td><?=$l['total']?></td><td><?=htmlspecialchars($l['fecha_creacion'])?></td><td><div class="actions"><a class="btn small" href="ver.php?id=<?=$l['id_lista']?>">Administrar / ver lista</a><form method="post" onsubmit="return confirm('¿Eliminar esta lista?');"><input type="hidden" name="action" value="eliminar"><input type="hidden" name="id_lista" value="<?=$l['id_lista']?>"><button class="btn danger small">Eliminar</button></form></div></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
<?php require __DIR__.'/../includes/footer.php'; ?>
