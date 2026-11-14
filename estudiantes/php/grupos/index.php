<?php
require_once __DIR__ . '/../config/database.php';
$pageTitle='Creación y administración de grupos';
$msg='';$cursos=$pdo->query("SELECT id_curso,nombre FROM cursos ORDER BY nombre")->fetchAll();
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $action=$_POST['action'] ?? '';
  if ($action==='crear') {
    $nombre=trim($_POST['nombre_grupo'] ?? '');
    if ($nombre!=='') {
      $st=$pdo->prepare("INSERT INTO grupos(nombre_grupo,descripcion,horario,modalidad,cupo,id_curso) VALUES(?,?,?,?,?,?)");
      $st->execute([$nombre,trim($_POST['descripcion'] ?? ''),trim($_POST['horario'] ?? ''),$_POST['modalidad'] ?? 'presencial',max(1,(int)($_POST['cupo'] ?? 15)),((int)($_POST['id_curso'] ?? 0))?:null]);
      $msg='Grupo creado correctamente.';
    } else $msg='Escribe el nombre del grupo.';
  } elseif ($action==='eliminar') {
    $st=$pdo->prepare("DELETE FROM grupos WHERE id_grupo=?"); $st->execute([(int)$_POST['id_grupo']]); $msg='Grupo eliminado.';
  }
}
$grupos=$pdo->query("SELECT g.*,COUNT(ga.id_usuario) total FROM grupos g LEFT JOIN grupo_alumnos ga ON ga.id_grupo=g.id_grupo GROUP BY g.id_grupo ORDER BY g.fecha_creacion DESC")->fetchAll();
require __DIR__.'/../includes/header.php';
?>
<h1>Creación de grupos</h1><p class="muted">Crea grupos, consulta sus integrantes y administra quién pertenece a cada uno.</p>
<?php if($msg): ?><div class="alert"><?=htmlspecialchars($msg)?></div><?php endif; ?>
<section class="card">
<h2>Nuevo grupo</h2>
<form method="post" class="grid">
<input type="hidden" name="action" value="crear">
<div><label>Nombre del grupo *</label><input name="nombre_grupo" required placeholder="Ej. Inglés A1 - Matutino"></div>
<div><label>Horario</label><input name="horario" placeholder="Ej. Lunes y miércoles 9:00 a 11:00"></div>
<div><label>Modalidad</label><select name="modalidad"><option value="presencial">Presencial</option><option value="en_linea">En línea</option><option value="mixta">Mixta</option></select></div>
<div><label>Curso</label><select name="id_curso"><option value="">Sin curso</option><?php foreach($cursos as $cu): ?><option value="<?=$cu['id_curso']?>"><?=htmlspecialchars($cu['nombre'])?></option><?php endforeach; ?></select></div>
<div><label>Cupo</label><input type="number" name="cupo" value="15" min="1"></div>
<div><label>Descripción</label><input name="descripcion" placeholder="Descripción opcional"></div>
<div style="align-self:end"><button class="btn">Crear grupo</button></div>
</form></section>
<section class="card"><h2>Grupos registrados</h2>
<?php if(!$grupos): ?><p class="muted">Todavía no hay grupos creados.</p><?php else: ?>
<div class="table-wrap"><table><thead><tr><th>Grupo</th><th>Horario</th><th>Modalidad</th><th>Alumnos</th><th>Acciones</th></tr></thead><tbody>
<?php foreach($grupos as $g): ?><tr>
<td><b><?=htmlspecialchars($g['nombre_grupo'])?></b><br><span class="muted"><?=htmlspecialchars($g['descripcion'] ?? '')?></span></td>
<td><?=htmlspecialchars($g['horario'] ?? '—')?></td><td><span class="tag"><?=htmlspecialchars($g['modalidad'])?></span></td><td><?=$g['total']?></td>
<td><div class="actions"><a class="btn small" href="ver.php?id=<?=$g['id_grupo']?>">Administrar alumnos</a>
<form class="inline" method="post" onsubmit="return confirm('¿Eliminar este grupo?');"><input type="hidden" name="action" value="eliminar"><input type="hidden" name="id_grupo" value="<?=$g['id_grupo']?>"><button class="btn danger small">Eliminar</button></form></div></td>
</tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
<?php require __DIR__.'/../includes/footer.php'; ?>
