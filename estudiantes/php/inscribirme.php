<?php
define('PUBLICO',1);
require_once __DIR__.'/config/database.php';
$id=(int)($_GET['id'] ?? $_POST['id'] ?? 0); $al=alumnoActual(); $v=$_POST+['nombre'=>$al['nombre'] ?? '','apellidos'=>'','correo'=>$al['email'] ?? '','telefono'=>''];
$st=$pdo->prepare("SELECT * FROM cursos WHERE id_curso=? AND estado='activo'"); $st->execute([$id]); $c=$st->fetch();
if(!$c){ header('Location: index.php#idiomas'); exit; }
$grupos=gruposDeCurso($pdo,$id);
$aviso=''; $done=null;
if($_SERVER['REQUEST_METHOD']==='POST'){
  $gid=(int)($_POST['grupo'] ?? 0); $g=null; foreach($grupos as $x) if($x['id_grupo']==$gid) $g=$x;
  $nom=trim($_POST['nombre'] ?? ''); $ape=trim($_POST['apellidos'] ?? ''); $cor=trim($_POST['correo'] ?? ''); $tel=trim($_POST['telefono'] ?? '');
  if(!$g || $nom===''||$ape===''||$tel===''||!filter_var($cor,FILTER_VALIDATE_EMAIL) || empty($_POST['acepto'])) $aviso='Completa todos los campos obligatorios, elige un grupo y acepta la política de privacidad.';
  else{
    $u=$pdo->prepare("SELECT id_usuario FROM usuarios WHERE email=?"); $u->execute([$cor]); $uid=(int)$u->fetchColumn();
    if(!$uid){ $pdo->prepare("INSERT INTO usuarios(nombre,apellido,email,password,telefono) VALUES(?,?,?,?,?)")->execute([$nom,$ape,$cor,password_hash(bin2hex(random_bytes(6)),PASSWORD_DEFAULT),$tel]); $uid=(int)$pdo->lastInsertId(); }
    $d=$pdo->prepare("SELECT COUNT(*) FROM inscripciones WHERE id_usuario=? AND id_grupo=? AND estado<>'cancelada'"); $d->execute([$uid,$gid]);
    if($d->fetchColumn()>0) $aviso='Ya existe una solicitud con ese correo en este grupo.';
    else{
      $lleno=(int)$g['ocup']>=(int)$g['cupo']; $est=$lleno?'lista_espera':'pendiente';
      $pdo->prepare("INSERT INTO inscripciones(id_usuario,id_curso,id_grupo,estado) VALUES(?,?,?,?)")->execute([$uid,$id,$gid,$est]);
      $done=['lleno'=>$lleno,'folio'=>'INS-'.str_pad($pdo->lastInsertId(),4,'0',STR_PAD_LEFT),'grupo'=>$g['nombre_grupo'],'correo'=>$cor];
    }
  }
}
$titulo='Inscripción';
require __DIR__.'/includes/public_header.php';
$info=[]; foreach($grupos as $g){ $info[$g['id_grupo']]=[$g['nombre_grupo'],$g['horario'] ?: '—',modalidadTxt($g['modalidad']),max(0,(int)$g['cupo']-(int)$g['ocup'])]; }
?>
<main class="enroll"><div class="wrap grid">
<div>
  <div class="eyebrow">Inscripción · <?= htmlspecialchars($c['nombre']) ?></div><h1>Reserva tu lugar.</h1>
  <p class="lead">Elige el grupo que mejor se adapte a tu día a día. Es una solicitud sin costo: Servicios Escolares te contactará para confirmar tu inscripción.</p>
  <div class="form-card">
  <?php if($done): ?>
    <div class="done-box show"><div class="big"><?= $done['lleno']?'Estás en la lista de espera':'¡Solicitud enviada!' ?></div><div class="folio">Folio <?= $done['folio'] ?></div>
    <p style="max-width:460px;margin:0 auto 20px"><?= $done['lleno'] ? 'El grupo '.htmlspecialchars($done['grupo']).' está lleno. Te avisaremos cuando se libere un lugar.' : 'Tu solicitud para '.htmlspecialchars($done['grupo']).' quedó registrada. Servicios Escolares te contactará a '.htmlspecialchars($done['correo']).' para confirmar inscripción y pago.' ?></p>
    <a class="btn-green" href="index.php#idiomas">Explorar más idiomas</a></div>
  <?php else: ?>
    <form method="post"><input type="hidden" name="id" value="<?= $id ?>">
      <h3>Tus datos</h3>
      <div class="two"><div class="ff"><label>Nombre <span class="req">*</span></label><input name="nombre" value="<?= htmlspecialchars($v['nombre']) ?>" placeholder="Tu nombre" required></div>
      <div class="ff"><label>Apellidos <span class="req">*</span></label><input name="apellidos" value="<?= htmlspecialchars($v['apellidos']) ?>" placeholder="Tus apellidos" required></div>
      <div class="ff"><label>Correo electrónico <span class="req">*</span></label><input type="email" name="correo" value="<?= htmlspecialchars($v['correo']) ?>" placeholder="tu@correo.com" required></div>
      <div class="ff"><label>Teléfono <span class="req">*</span></label><input type="tel" name="telefono" value="<?= htmlspecialchars($v['telefono']) ?>" placeholder="Número de contacto" required></div></div>
      <h3 style="margin-top:10px">Tu grupo</h3>
      <div class="ff"><label>Grupo, horario y modalidad <span class="req">*</span></label>
        <select name="grupo" id="grupo" required><option value="">Selecciona un grupo</option><?php foreach($grupos as $g): ?><option value="<?= $g['id_grupo'] ?>"><?= htmlspecialchars($g['nombre_grupo'].' · '.($g['horario'] ?: '—').' · '.modalidadTxt($g['modalidad'])) ?></option><?php endforeach; ?></select>
        <div class="help">Si no sabes qué nivel te corresponde, elige el básico: te orientaremos con una valoración de nivel.</div></div>
      <div class="chk-row"><input type="checkbox" name="acepto" value="1" id="acepto"><label for="acepto">He leído la política de privacidad y acepto que me contacten para confirmar mi inscripción. *</label></div>
      <?php if($aviso): ?><div class="notice err show"><strong>Revisa tu solicitud</strong><?= htmlspecialchars($aviso) ?></div><?php endif; ?>
      <?php if(!$grupos): ?><div class="notice warn show"><strong>Sin grupos disponibles</strong>Este curso aún no tiene grupos publicados.</div><?php endif; ?>
      <div style="display:flex;gap:10px;flex-wrap:wrap"><button class="btn-green" type="submit">Enviar solicitud ↗</button><a class="btn-ghost" href="curso.php?id=<?= $id ?>">Volver al curso</a></div>
    </form>
  <?php endif; ?>
  </div>
</div>
<aside class="summary"><h3>Resumen de tu solicitud</h3>
  <dl><dt>Curso</dt><dd><?= htmlspecialchars($c['nombre']) ?></dd><dt>Grupo</dt><dd id="rG">—</dd><dt>Horario</dt><dd id="rH">—</dd><dt>Modalidad</dt><dd id="rM">—</dd><dt>Disponibilidad</dt><dd id="rD">—</dd></dl>
  <ol class="steps"><li>Envías tu solicitud.</li><li>Servicios Escolares valida tus datos y tu lugar.</li><li>Te contactan para confirmar inscripción y pago.</li></ol>
</aside>
</div></main>
<script>
var G=<?= json_encode($info) ?>,s=document.getElementById('grupo');
if(s)s.addEventListener('change',function(){var g=G[s.value];document.getElementById('rG').textContent=g?g[0]:'—';document.getElementById('rH').textContent=g?g[1]:'—';document.getElementById('rM').textContent=g?g[2]:'—';
document.getElementById('rD').innerHTML=!g?'—':(g[3]>0?'<span class="pill ok">'+g[3]+' lugares disponibles</span>':'<span class="pill warn">Grupo lleno · lista de espera</span>');});
</script>
<?php require __DIR__.'/includes/public_footer.php'; ?>
