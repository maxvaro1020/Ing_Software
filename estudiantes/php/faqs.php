<?php
require_once __DIR__.'/config/database.php';
$pageTitle='Preguntas frecuentes'; $msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $a=$_POST['action'] ?? ''; $id=(int)($_POST['id'] ?? 0);
  if($a==='responder' && trim($_POST['respuesta'])!==''){ $pdo->prepare("UPDATE faqs SET respuesta=?,categoria='general' WHERE id_faq=?")->execute([trim($_POST['respuesta']),$id]); $msg='Respuesta publicada en la página principal.'; }
  if($a==='crear' && trim($_POST['pregunta'])!=='' && trim($_POST['respuesta'])!==''){ $pdo->prepare("INSERT INTO faqs(pregunta,respuesta,categoria) VALUES(?,?,'general')")->execute([trim($_POST['pregunta']),trim($_POST['respuesta'])]); $msg='Pregunta publicada.'; }
  if($a==='eliminar'){ $pdo->prepare("DELETE FROM faqs WHERE id_faq=?")->execute([$id]); $msg='Pregunta eliminada.'; }
}
$pend=$pdo->query("SELECT * FROM faqs WHERE categoria='pendiente' ORDER BY id_faq DESC")->fetchAll();
$pub=$pdo->query("SELECT * FROM faqs WHERE COALESCE(categoria,'')<>'pendiente' ORDER BY id_faq")->fetchAll();
require __DIR__.'/includes/header.php';
?>
<h1>Preguntas frecuentes</h1><p class="muted">Responde las dudas recibidas desde la página principal y publícalas.</p>
<?php if($msg): ?><div class="alert"><?=$msg?></div><?php endif; ?>
<section class="card"><h2>Preguntas por responder (<?=count($pend)?>)</h2>
<?php foreach($pend as $f): ?><form method="post" style="border-top:1px solid var(--line);padding:12px 0"><input type="hidden" name="action" value="responder"><input type="hidden" name="id" value="<?=$f['id_faq']?>">
<b><?=htmlspecialchars($f['pregunta'])?></b><textarea name="respuesta" rows="2" placeholder="Escribe la respuesta" required></textarea>
<div class="actions" style="margin-top:8px"><button class="btn small">Responder y publicar</button><button class="btn danger small" name="action" value="eliminar" formnovalidate>Descartar</button></div></form><?php endforeach; if(!$pend): ?><p class="muted">No hay preguntas pendientes.</p><?php endif; ?></section>
<section class="card"><h2>Nueva pregunta</h2><form method="post"><input type="hidden" name="action" value="crear"><label>Pregunta *</label><input name="pregunta" required><label>Respuesta *</label><textarea name="respuesta" rows="3" required></textarea><div class="actions" style="margin-top:10px"><button class="btn">Publicar</button></div></form></section>
<section class="card"><h2>Publicadas</h2><div class="table-wrap"><table><tbody>
<?php foreach($pub as $f): ?><tr><td><b><?=htmlspecialchars($f['pregunta'])?></b><br><small class="muted"><?=htmlspecialchars(mb_strimwidth((string)$f['respuesta'],0,110,'…'))?></small></td>
<td><form method="post" onsubmit="return confirm('¿Eliminar?')"><input type="hidden" name="action" value="eliminar"><input type="hidden" name="id" value="<?=$f['id_faq']?>"><button class="btn danger small">Eliminar</button></form></td></tr><?php endforeach; ?></tbody></table></div></section>
<?php require __DIR__.'/includes/footer.php'; ?>
