<?php
define('FINANZAS',1);
require_once __DIR__.'/../config/database.php';
$pageTitle='Pagos'; $msg=''; $err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $a=$_POST['action']??''; $id=(int)($_POST['id']??0);
  if($a==='aprobar'||$a==='rechazar'){
    $obs=trim($_POST['observaciones']??'');
    if($a==='rechazar' && $obs==='') $err='El motivo es obligatorio al rechazar un pago.';
    else{
      // Solo se decide sobre pagos pendientes; nunca se toca la inscripción: eso lo decide Administración.
      $n=$pdo->prepare("UPDATE pagos SET estado_pago=?,observaciones=?,validado_por=?,fecha_validacion=NOW() WHERE id_pago=? AND estado_pago='pendiente'");
      $n->execute([$a==='aprobar'?'aprobado':'rechazado',$obs!==''?$obs:null,FIN_EMAIL,$id]);
      $msg=$n->rowCount()?($a==='aprobar'?'Pago validado. Administración ya verá la inscripción como «Pagado».':'Pago rechazado. Administración lo verá como «Rechazado».'):'Ese pago ya había sido resuelto.';
    }
  }
  if($a==='crear'){
    $insc=(int)($_POST['insc']??0); $monto=(float)($_POST['monto']??0);
    if(!$insc||$monto<=0) $err='Elige una inscripción e indica un monto mayor a cero.';
    else{ $pdo->prepare("INSERT INTO pagos(id_inscripcion,monto,comprobante_url) VALUES(?,?,?)")->execute([$insc,$monto,trim($_POST['url']??'')?:null]); $msg='Pago registrado.'; }
  }
}
$t=$_GET['t']??'pendientes'; $sel=(int)($_GET['id']??0); $b=trim($_GET['q']??'');
$sql="SELECT p.*,u.nombre,u.apellido,u.email,g.nombre_grupo FROM pagos p JOIN inscripciones i ON i.id_inscripcion=p.id_inscripcion JOIN usuarios u ON u.id_usuario=i.id_usuario LEFT JOIN grupos g ON g.id_grupo=i.id_grupo WHERE CONCAT(u.nombre,' ',u.apellido) LIKE :q".($t==='todos'?'':" AND p.estado_pago='pendiente'")." ORDER BY p.id_pago DESC";
$st=$pdo->prepare($sql); $st->execute([':q'=>"%$b%"]); $pagos=$st->fetchAll(); $cur=null; foreach($pagos as $p) if($p['id_pago']==$sel) $cur=$p;
$ins=$pdo->query("SELECT i.id_inscripcion,u.nombre,u.apellido,g.nombre_grupo FROM inscripciones i JOIN usuarios u ON u.id_usuario=i.id_usuario LEFT JOIN grupos g ON g.id_grupo=i.id_grupo WHERE i.estado<>'cancelada' ORDER BY i.id_inscripcion DESC")->fetchAll();
$qs=fn($x)=>http_build_query(array_filter(['t'=>$t,'q'=>$b]+$x));
require __DIR__.'/../includes/header_fin.php';
?>
<h1>Pagos</h1><p class="muted">Revisa comprobantes y valida o rechaza cada pago. Administración solo verá el resultado (Pagado / Rechazado), sin montos ni comprobantes.</p>
<?php if($msg): ?><div class="alert"><?=htmlspecialchars($msg)?></div><?php endif; if($err): ?><div class="alert error"><?=htmlspecialchars($err)?></div><?php endif; ?>
<details class="card"><summary class="btn small">+ Registrar pago</summary><form method="post" class="grid"><input type="hidden" name="action" value="crear">
<div><label>Inscripción *</label><select name="insc" required><?php foreach($ins as $x): ?><option value="<?=$x['id_inscripcion']?>">INS-<?=str_pad($x['id_inscripcion'],4,'0',STR_PAD_LEFT)?> · <?=htmlspecialchars($x['nombre'].' '.$x['apellido'].' · '.($x['nombre_grupo']??''))?></option><?php endforeach; ?></select></div>
<div><label>Monto *</label><input type="number" step="0.01" min="0.01" name="monto" required></div><div><label>URL del comprobante</label><input name="url"></div>
<div style="align-self:end"><button class="btn">Guardar</button></div></form></details>
<div class="split"><section class="card" style="margin:0">
<div class="tabs"><a class="btn small <?=$t==='todos'?'secondary':''?>" href="?<?=http_build_query(array_filter(['t'=>'pendientes','q'=>$b]))?>">Pendientes</a><a class="btn small <?=$t==='todos'?'':'secondary'?>" href="?<?=http_build_query(array_filter(['t'=>'todos','q'=>$b]))?>">Todos</a></div>
<form method="get" style="margin-bottom:8px"><input type="hidden" name="t" value="<?=htmlspecialchars($t)?>"><input name="q" value="<?=htmlspecialchars($b)?>" placeholder="Buscar alumno"></form>
<?php foreach($pagos as $p): ?><a class="pg <?=$p['id_pago']==$sel?'on':''?>" href="?<?=$qs(['id'=>$p['id_pago']])?>"><b>PG-<?=str_pad($p['id_pago'],4,'0',STR_PAD_LEFT)?></b> <span style="float:right"><b>$<?=number_format($p['monto'],2)?></b></span><br><small class="muted"><?=htmlspecialchars($p['nombre'].' '.$p['apellido'].' · '.($p['nombre_grupo']??''))?></small><br><?=tagEstado($p['estado_pago'])?></a><?php endforeach; if(!$pagos): ?><p class="muted">Sin pagos.</p><?php endif; ?></section>
<section class="card" style="margin:0"><?php if(!$cur): ?><div style="text-align:center;padding:30px"><b>Selecciona un pago</b><p class="muted">Elige un pago de la lista para revisar su comprobante.</p></div><?php else: ?>
<h2>PG-<?=str_pad($cur['id_pago'],4,'0',STR_PAD_LEFT)?> <?=tagEstado($cur['estado_pago'])?></h2>
<p><b><?=htmlspecialchars($cur['nombre'].' '.$cur['apellido'])?></b><br><span class="muted"><?=htmlspecialchars($cur['email'])?> · <?=htmlspecialchars($cur['nombre_grupo']??'')?></span></p>
<p>Monto: <b>$<?=number_format($cur['monto'],2)?></b><br>Fecha: <?=date('d/m/Y',strtotime($cur['fecha_pago']))?></p>
<p>Comprobante: <?php if($cur['comprobante_url']): ?><a href="<?=htmlspecialchars($cur['comprobante_url'])?>" target="_blank" rel="noopener noreferrer">Ver comprobante</a><?php else: ?><span class="muted">No adjunto</span><?php endif; ?></p>
<?php if($cur['estado_pago']==='pendiente'): ?>
<form method="post"><input type="hidden" name="id" value="<?=$cur['id_pago']?>">
<label for="obs">Observaciones <span class="muted">(obligatorias si rechazas)</span></label><textarea id="obs" name="observaciones" placeholder="Motivo del rechazo o nota interna"></textarea>
<div class="actions" style="margin-top:12px"><button class="btn" name="action" value="aprobar">Validar pago</button><button class="btn danger" name="action" value="rechazar">Rechazar</button></div></form>
<?php else: ?><p class="muted">Resuelto el <?=$cur['fecha_validacion']?date('d/m/Y H:i',strtotime($cur['fecha_validacion'])):'—'?><?=!empty($cur['observaciones'])?' · Motivo/nota: '.htmlspecialchars($cur['observaciones']):''?></p><?php endif; endif; ?></section></div>
<?php require __DIR__.'/../includes/footer.php'; ?>
