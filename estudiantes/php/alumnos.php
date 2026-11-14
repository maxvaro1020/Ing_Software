<?php
require_once __DIR__.'/config/database.php';
$pageTitle='Alumnos'; $msg=''; $err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(($_POST['action']??'')==='eliminar'){ $pdo->prepare("DELETE FROM usuarios WHERE id_usuario=?")->execute([(int)$_POST['id']]); $msg='Alumno eliminado.'; }
  if(($_POST['action']??'')==='crear'){
    try{ $pdo->prepare("INSERT INTO usuarios(nombre,apellido,email,password,telefono) VALUES(?,?,?,?,?)")->execute([trim($_POST['nombre']),trim($_POST['apellido']),trim($_POST['email']),password_hash($_POST['password'],PASSWORD_DEFAULT),trim($_POST['telefono'])?:null]);
      $msg='Alumno registrado con la matrícula AL-'.str_pad($pdo->lastInsertId(),4,'0',STR_PAD_LEFT); }
    catch(PDOException $e){ $err='No se pudo registrar (¿correo repetido?).'; }
  }
}
$q=trim($_GET['q']??''); $g=(int)($_GET['g']??0);
$sql="SELECT u.id_usuario,u.nombre,u.apellido,u.email,u.telefono,u.fecha_registro,n.nombre_nivel FROM usuarios u LEFT JOIN expedientes e ON e.id_usuario=u.id_usuario LEFT JOIN niveles n ON n.id_nivel=e.id_nivel WHERE (CONCAT(u.nombre,' ',u.apellido) LIKE :q1 OR u.email LIKE :q2 OR u.telefono LIKE :q3)";
$par=[':q1'=>"%$q%",':q2'=>"%$q%",':q3'=>"%$q%"];
if($g){$sql.=" AND EXISTS(SELECT 1 FROM grupo_alumnos x WHERE x.id_usuario=u.id_usuario AND x.id_grupo=:g)";$par[':g']=$g;}
$st=$pdo->prepare($sql." ORDER BY u.id_usuario DESC"); $st->execute($par); $alumnos=$st->fetchAll();
$gs=$pdo->query("SELECT id_grupo,nombre_grupo FROM grupos ORDER BY nombre_grupo")->fetchAll();
$tags=[]; foreach($pdo->query("SELECT ga.id_usuario,g.nombre_grupo FROM grupo_alumnos ga JOIN grupos g ON g.id_grupo=ga.id_grupo") as $r) $tags[$r['id_usuario']][]=$r['nombre_grupo'];
require __DIR__.'/includes/header.php';
?>
<div class="actions" style="justify-content:space-between"><div><h1>Alumnos</h1><p class="muted">Consulta, registra y elimina alumnos. Cada alumno recibe una matrícula automática.</p></div>
<div><button class="btn" onclick="nuevo.showModal()">+ Nuevo alumno</button></div></div>
<?php if($msg): ?><div class="alert"><?=htmlspecialchars($msg)?></div><?php endif; if($err): ?><div class="alert error"><?=$err?></div><?php endif; ?>
<section class="card">
<form class="toolbar" method="get"><input name="q" value="<?=htmlspecialchars($q)?>" placeholder="Buscar por nombre, correo o teléfono">
<select name="g" onchange="this.form.submit()"><option value="0">Todos los grupos</option><?php foreach($gs as $x): ?><option value="<?=$x['id_grupo']?>" <?=$g==$x['id_grupo']?'selected':''?>><?=htmlspecialchars($x['nombre_grupo'])?></option><?php endforeach; ?></select>
<span class="muted" style="align-self:center"><?=count($alumnos)?> alumnos</span></form>
<div class="table-wrap"><table><thead><tr><th>Matrícula</th><th>Alumno</th><th>Contacto</th><th>Nivel</th><th>Grupos</th><th></th></tr></thead><tbody>
<?php foreach($alumnos as $a): $mat='AL-'.str_pad($a['id_usuario'],4,'0',STR_PAD_LEFT); $nom=htmlspecialchars($a['nombre'].' '.$a['apellido']); ?><tr>
<td><b><?=$mat?></b></td><td><b><?=$nom?></b><br><small class="muted">Registro: <?=date('d/m/Y',strtotime($a['fecha_registro']))?></small></td>
<td><?=htmlspecialchars($a['email'])?><br><small class="muted"><?=htmlspecialchars($a['telefono']??'')?></small></td><td><?=htmlspecialchars($a['nombre_nivel']??'Sin asignar')?></td>
<td><?php foreach($tags[$a['id_usuario']]??[] as $t): ?><span class="tag"><?=htmlspecialchars($t)?></span> <?php endforeach; ?></td>
<td><button type="button" class="btn danger small" onclick="del(<?=$a['id_usuario']?>,<?=htmlspecialchars(json_encode($a['nombre'].' '.$a['apellido']))?>,'<?=$mat?>')">Eliminar</button></td></tr><?php endforeach; ?>
</tbody></table></div></section>
<dialog id="dlg"><form method="post"><h2>Eliminar alumno</h2><p id="dtxt" class="muted"></p>
<input type="hidden" name="action" value="eliminar"><input type="hidden" name="id" id="did">
<div class="actions"><button type="button" class="btn secondary" onclick="dlg.close()">Cancelar</button><button class="btn danger">Sí, eliminar</button></div></form></dialog>
<dialog id="nuevo"><form method="post"><h2>Nuevo alumno</h2><input type="hidden" name="action" value="crear">
<label>Nombre *</label><input name="nombre" required><label>Apellido *</label><input name="apellido" required><label>Correo *</label><input type="email" name="email" required>
<label>Teléfono</label><input name="telefono"><label>Contraseña *</label><input type="password" name="password" required>
<div class="actions" style="margin-top:14px"><button type="button" class="btn secondary" onclick="nuevo.close()">Cancelar</button><button class="btn">Registrar</button></div></form></dialog>
<script>function del(i,n,m){did.value=i;dtxt.textContent='Vas a eliminar a '+n+' ('+m+'). También se borrarán su expediente, inscripciones y pagos. Esta acción no se puede deshacer.';dlg.showModal()}</script>
<?php require __DIR__.'/includes/footer.php'; ?>
