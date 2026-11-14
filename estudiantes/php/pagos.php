<?php
require_once __DIR__.'/config/database.php';
$pageTitle='Pagos'; $msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $a=$_POST['action']??''; $id=(int)($_POST['id']??0);
  if($a==='aprobar'){ $pdo->prepare("UPDATE pagos SET estado_pago='aprobado',fecha_validacion=NOW() WHERE id_pago=?")->execute([$id]);
    $pdo->prepare("UPDATE inscripciones SET estado='confirmada' WHERE id_inscripcion=(SELECT id_inscripcion FROM pagos WHERE id_pago=?)")->execute([$id]); $msg='Pago aprobado e inscripción confirmada.'; }
  if($a==='rechazar'){ $pdo->prepare("UPDATE pagos SET estado_pago='rechazado',fecha_validacion=NOW() WHERE id_pago=?")->execute([$id]); $msg='Pago rechazado.'; }
  if($a==='crear'){ $pdo->prepare("INSERT INTO pagos(id_inscripcion,monto,comprobante_url) VALUES(?,?,?)")->execute([(int)$_POST['insc'],(float)$_POST['monto'],trim($_POST['url'])?:null]); $msg='Pago registrado.'; }
}
$t=$_GET['t']??'pendientes'; $sel=(int)($_GET['id']??0);
$sql="SELECT p.*,u.nombre,u.apellido,g.nombre_grupo FROM pagos p JOIN inscripciones i ON i.id_inscripcion=p.id_inscripcion JOIN usuarios u ON u.id_usuario=i.id_usuario LEFT JOIN grupos g ON g.id_grupo=i.id_grupo".($t==='todos'?'':" WHERE p.estado_pago='pendiente'")." ORDER BY p.id_pago DESC";
$pagos=$pdo->query($sql)->fetchAll(); $cur=null; foreach($pagos as $p) if($p['id_pago']==$sel) $cur=$p;
$ins=$pdo->query("SELECT i.id_inscripcion,u.nombre,u.apellido,g.nombre_grupo FROM inscripciones i JOIN usuarios u ON u.id_usuario=i.id_usuario LEFT JOIN grupos g ON g.id_grupo=i.id_grupo WHERE i.estado<>'cancelada' ORDER BY i.id_inscripcion DESC")->fetchAll();
require __DIR__.'/includes/header.php';
?>
<h1>Pagos</h1><p class="muted">Revisa comprobantes y actualiza el estado del pago y de la inscripción.</p>
<?php if($msg): ?><div class="alert"><?=$msg?></div><?php endif; ?>
<details class="card"><summary class="btn small">+ Registrar pago</summary><form method="post" class="grid"><input type="hidden" name="action" value="crear">
<div><label>Inscripción *</label><select name="insc" required><?php foreach($ins as $x): ?><option value="<?=$x['id_inscripcion']?>">INS-<?=str_pad($x['id_inscripcion'],4,'0',STR_PAD_LEFT)?> · <?=htmlspecialchars($x['nombre'].' '.$x['apellido'].' · '.($x['nombre_grupo']??''))?></option><?php endforeach; ?></select></div>
<div><label>Monto *</label><input type="number" step="0.01" min="0" name="monto" required></div><div><label>URL del comprobante</label><input name="url"></div>
<div style="align-self:end"><button class="btn">Guardar</button></div></form></details>
<div class="split"><section class="card" style="margin:0"><div class="tabs"><a class="btn small <?=$t==='todos'?'secondary':''?>" href="?t=pendientes">Pendientes</a><a class="btn small <?=$t==='todos'?'':'secondary'?>" href="?t=todos">Todos</a></div>
<?php foreach($pagos as $p): ?><a class="pg <?=$p['id_pago']==$sel?'on':''?>" href="?t=<?=$t?>&id=<?=$p['id_pago']?>"><b>PG-<?=str_pad($p['id_pago'],4,'0',STR_PAD_LEFT)?></b> <span style="float:right"><b>$<?=number_format($p['monto'],2)?></b></span><br><small class="muted"><?=htmlspecialchars($p['nombre'].' '.$p['apellido'].' · '.($p['nombre_grupo']??''))?></small><br><?=tagEstado($p['estado_pago'])?></a><?php endforeach; if(!$pagos): ?><p class="muted">Sin pagos.</p><?php endif; ?></section>
<section class="card" style="margin:0"><?php if(!$cur): ?><div style="text-align:center;padding:30px"><b>Selecciona un pago</b><p class="muted">Elige un pago de la lista para revisar su comprobante.</p></div><?php else: ?>
<h2>PG-<?=str_pad($cur['id_pago'],4,'0',STR_PAD_LEFT)?> <?=tagEstado($cur['estado_pago'])?></h2>
<p><b><?=htmlspecialchars($cur['nombre'].' '.$cur['apellido'])?></b><br><span class="muted"><?=htmlspecialchars($cur['nombre_grupo']??'')?></span></p>
<p>Monto: <b>$<?=number_format($cur['monto'],2)?></b><br>Fecha: <?=date('d/m/Y',strtotime($cur['fecha_pago']))?></p>
<p>Comprobante: <?php if($cur['comprobante_url']): ?><a href="<?=htmlspecialchars($cur['comprobante_url'])?>" target="_blank">Ver comprobante</a><?php else: ?><span class="muted">No adjunto</span><?php endif; ?></p>
<?php if($cur['estado_pago']==='pendiente'): ?><form method="post" class="actions"><input type="hidden" name="id" value="<?=$cur['id_pago']?>"><button class="btn" name="action" value="aprobar">Aprobar pago</button><button class="btn danger" name="action" value="rechazar">Rechazar</button></form><?php endif; endif; ?></section></div>
<?php require __DIR__.'/includes/footer.php'; ?>
