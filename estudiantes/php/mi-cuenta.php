<?php
define('PUBLICO',1);
require_once __DIR__.'/config/database.php';
$al=alumnoActual(); if(!$al){ header('Location: index.php?login=1&next=mi-cuenta.php'); exit; }
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action'] ?? '')==='pago'){
  $iid=(int)$_POST['insc']; $ok=$pdo->prepare("SELECT 1 FROM inscripciones WHERE id_inscripcion=? AND id_usuario=?"); $ok->execute([$iid,$al['id']]);
  if($ok->fetchColumn() && (float)$_POST['monto']>0){ $pdo->prepare("INSERT INTO pagos(id_inscripcion,monto,comprobante_url) VALUES(?,?,?)")->execute([$iid,(float)$_POST['monto'],trim($_POST['url'] ?? '')?:null]); $msg='Pago reportado. Será validado por Servicios Financieros.'; }
}
$st=$pdo->prepare("SELECT i.*,g.nombre_grupo,g.horario,c.nombre curso FROM inscripciones i LEFT JOIN grupos g ON g.id_grupo=i.id_grupo LEFT JOIN cursos c ON c.id_curso=i.id_curso WHERE i.id_usuario=? ORDER BY i.id_inscripcion DESC"); $st->execute([$al['id']]); $ins=$st->fetchAll();
$st=$pdo->prepare("SELECT p.* FROM pagos p JOIN inscripciones i ON i.id_inscripcion=p.id_inscripcion WHERE i.id_usuario=? ORDER BY p.id_pago DESC"); $st->execute([$al['id']]); $pagos=$st->fetchAll();
$titulo='Mi cuenta';
require __DIR__.'/includes/public_header.php';
?>
<main class="enroll"><div class="wrap">
  <div class="eyebrow">Mi cuenta</div><h1>Hola, <?= htmlspecialchars($al['nombre']) ?>.</h1>
  <?php if($msg): ?><div class="notice ok show"><?= $msg ?></div><?php endif; ?>
  <div class="form-card"><h3>Mis inscripciones</h3>
  <table class="sched"><thead><tr><th>Folio</th><th>Curso</th><th>Grupo</th><th>Estado</th></tr></thead><tbody>
  <?php foreach($ins as $i): ?><tr><td><b>INS-<?= str_pad($i['id_inscripcion'],4,'0',STR_PAD_LEFT) ?></b></td><td><?= htmlspecialchars((string)$i['curso']) ?></td><td><?= htmlspecialchars(($i['nombre_grupo'] ?? '—').' · '.($i['horario'] ?? '')) ?></td><td><?= pillEstado($i['estado']) ?></td></tr><?php endforeach; if(!$ins): ?><tr><td colspan="4">Aún no tienes inscripciones. <a href="index.php#idiomas">Explora los idiomas</a>.</td></tr><?php endif; ?>
  </tbody></table></div>
  <?php if($ins): ?><div class="form-card" style="margin-top:18px"><h3>Reportar un pago</h3>
  <form method="post"><input type="hidden" name="action" value="pago">
    <div class="ff"><label>Inscripción</label><select name="insc"><?php foreach($ins as $i): if($i['estado']==='cancelada') continue; ?><option value="<?= $i['id_inscripcion'] ?>">INS-<?= str_pad($i['id_inscripcion'],4,'0',STR_PAD_LEFT) ?> · <?= htmlspecialchars((string)$i['curso']) ?></option><?php endforeach; ?></select></div>
    <div class="ff"><label>Monto</label><input type="number" name="monto" step="0.01" min="1" required></div>
    <div class="ff"><label>Enlace al comprobante (opcional)</label><input name="url"></div>
    <button class="btn-green" type="submit">Enviar pago</button></form>
  <?php if($pagos): ?><h3 style="margin-top:18px">Mis pagos</h3><table class="sched"><tbody><?php foreach($pagos as $p): ?><tr><td>PG-<?= str_pad($p['id_pago'],4,'0',STR_PAD_LEFT) ?></td><td>$<?= number_format($p['monto'],2) ?></td><td><?= pillEstado($p['estado_pago']) ?></td></tr><?php endforeach; ?></tbody></table><?php endif; ?></div><?php endif; ?>
</div></main>
<?php require __DIR__.'/includes/public_footer.php'; ?>
