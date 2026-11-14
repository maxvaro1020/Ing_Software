<?php
require_once __DIR__.'/config/database.php';
$pageTitle='Inscripciones'; $msg=''; $err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(($_POST['action']??'')==='estado'){ $pdo->prepare("UPDATE inscripciones SET estado=? WHERE id_inscripcion=?")->execute([$_POST['estado'],(int)$_POST['id']]); $msg='Estado actualizado.'; }
  if(($_POST['action']??'')==='crear'){
    $u=(int)$_POST['alumno']; $gr=(int)$_POST['grupo']; $cu=(int)$_POST['curso']; $s2=$pdo->prepare("SELECT id_curso FROM grupos WHERE id_grupo=?"); $s2->execute([$gr]); $cu=(int)$s2->fetchColumn() ?: $cu;
    $st=$pdo->prepare("SELECT COUNT(*) FROM inscripciones WHERE id_usuario=? AND id_grupo=? AND estado<>'cancelada'"); $st->execute([$u,$gr]);
    if(!$u||!$gr||!$cu) $err='Completa alumno, grupo y curso.';
    elseif($st->fetchColumn()>0) $err='El alumno ya está inscrito en ese grupo.';
    else{
      $st=$pdo->prepare("SELECT cupo FROM grupos WHERE id_grupo=?"); $st->execute([$gr]); $cupo=(int)$st->fetchColumn();
      $st=$pdo->prepare("SELECT COUNT(*) FROM inscripciones WHERE id_grupo=? AND estado IN('pendiente','confirmada')"); $st->execute([$gr]); $ocup=(int)$st->fetchColumn();
      $est=$ocup>=$cupo?'lista_espera':'pendiente';
      $pdo->prepare("INSERT INTO inscripciones(id_usuario,id_curso,id_grupo,estado) VALUES(?,?,?,?)")->execute([$u,$cu,$gr,$est]);
      $msg=$est==='lista_espera'?'El grupo está lleno: el alumno pasó a lista de espera.':'Inscripción registrada.';
    }
  }
}
$q=trim($_GET['q']??''); $fe=$_GET['e']??'';
$sql="SELECT i.*,u.nombre,u.apellido,g.nombre_grupo,c.nombre curso FROM inscripciones i JOIN usuarios u ON u.id_usuario=i.id_usuario LEFT JOIN grupos g ON g.id_grupo=i.id_grupo LEFT JOIN cursos c ON c.id_curso=i.id_curso WHERE CONCAT(u.nombre,' ',u.apellido) LIKE :q";
$par=[':q'=>"%$q%"]; if($fe){$sql.=" AND i.estado=:e";$par[':e']=$fe;}
$st=$pdo->prepare($sql." ORDER BY i.id_inscripcion DESC"); $st->execute($par); $rows=$st->fetchAll();
$us=$pdo->query("SELECT id_usuario,nombre,apellido FROM usuarios ORDER BY apellido,nombre")->fetchAll();
$gs=$pdo->query("SELECT id_grupo,nombre_grupo FROM grupos WHERE estado='activo' ORDER BY nombre_grupo")->fetchAll();
$cs=$pdo->query("SELECT id_curso,nombre FROM cursos WHERE estado='activo' ORDER BY nombre")->fetchAll();
$ests=['pendiente'=>'Pendiente','confirmada'=>'Confirmada','cancelada'=>'Cancelada','lista_espera'=>'Lista de espera'];
require __DIR__.'/includes/header.php';
?>
<h1>Inscripciones</h1><p class="muted">Inscribe alumnos validando el cupo. Si el grupo está lleno, pasan a lista de espera.</p>
<?php if($msg): ?><div class="alert"><?=htmlspecialchars($msg)?></div><?php endif; if($err): ?><div class="alert error"><?=$err?></div><?php endif; ?>
<section class="card"><h2>Nueva inscripción</h2><form method="post" class="grid"><input type="hidden" name="action" value="crear">
<div><label>Alumno *</label><select name="alumno" required><option value="">Seleccionar alumno</option><?php foreach($us as $x): ?><option value="<?=$x['id_usuario']?>"><?=htmlspecialchars($x['nombre'].' '.$x['apellido'])?></option><?php endforeach; ?></select></div>
<div><label>Grupo *</label><select name="grupo" required><option value="">Seleccionar grupo</option><?php foreach($gs as $x): ?><option value="<?=$x['id_grupo']?>"><?=htmlspecialchars($x['nombre_grupo'])?></option><?php endforeach; ?></select></div>
<div><label>Curso (solo si el grupo no tiene)</label><select name="curso"><option value="">Seleccionar curso</option><?php foreach($cs as $x): ?><option value="<?=$x['id_curso']?>"><?=htmlspecialchars($x['nombre'])?></option><?php endforeach; ?></select></div>
<div style="align-self:end"><button class="btn">Inscribir</button></div></form></section>
<section class="card"><form class="toolbar" method="get"><input name="q" value="<?=htmlspecialchars($q)?>" placeholder="Buscar alumno">
<select name="e" onchange="this.form.submit()"><option value="">Todos los estados</option><?php foreach($ests as $k=>$v): ?><option value="<?=$k?>" <?=$fe===$k?'selected':''?>><?=$v?></option><?php endforeach; ?></select><span class="muted" style="align-self:center"><?=count($rows)?> registros</span></form>
<div class="table-wrap"><table><thead><tr><th>Folio</th><th>Alumno</th><th>Grupo</th><th>Fecha</th><th>Estado</th><th>Cambiar estado</th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td><b>INS-<?=str_pad($r['id_inscripcion'],4,'0',STR_PAD_LEFT)?></b></td>
<td><b><?=htmlspecialchars($r['nombre'].' '.$r['apellido'])?></b><br><small class="muted">AL-<?=str_pad($r['id_usuario'],4,'0',STR_PAD_LEFT)?></small></td>
<td><?=htmlspecialchars($r['nombre_grupo']??'Sin grupo')?><br><small class="muted"><?=htmlspecialchars($r['curso']??'')?></small></td>
<td><?=date('d/m/Y',strtotime($r['fecha_inscripcion']))?></td><td><?=tagEstado($r['estado'])?></td>
<td><form method="post"><input type="hidden" name="action" value="estado"><input type="hidden" name="id" value="<?=$r['id_inscripcion']?>"><select name="estado" onchange="this.form.submit()" style="width:auto"><?php foreach($ests as $k=>$v): ?><option value="<?=$k?>" <?=$r['estado']===$k?'selected':''?>><?=$v?></option><?php endforeach; ?></select></form></td></tr><?php endforeach; ?>
</tbody></table></div></section>
<?php require __DIR__.'/includes/footer.php'; ?>
