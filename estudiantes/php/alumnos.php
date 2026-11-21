<?php
require_once __DIR__.'/config/database.php';
$pageTitle='Alumnos'; $msg=''; $err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(($_POST['action']??'')==='eliminar'){ $pdo->prepare("DELETE FROM usuarios WHERE id_usuario=?")->execute([(int)$_POST['id']]); $msg='Alumno eliminado.'; }
  if(($_POST['action']??'')==='editar'){
    $id=(int)$_POST['id'];
    try{
      $pdo->beginTransaction();
      $pdo->prepare("UPDATE usuarios SET nombre=?,apellido=?,email=?,telefono=? WHERE id_usuario=?")->execute([trim($_POST['nombre']),trim($_POST['apellido']),trim($_POST['email']),trim($_POST['telefono'])?:null,$id]);
      if(trim($_POST['password']??'')!=='') $pdo->prepare("UPDATE usuarios SET password=? WHERE id_usuario=?")->execute([password_hash($_POST['password'],PASSWORD_DEFAULT),$id]);
      // Nivel (expediente)
      $niv=(int)($_POST['nivel']??0)?:null;
      $ex=$pdo->prepare("SELECT COUNT(*) FROM expedientes WHERE id_usuario=?"); $ex->execute([$id]);
      if($ex->fetchColumn()>0) $pdo->prepare("UPDATE expedientes SET id_nivel=? WHERE id_usuario=?")->execute([$niv,$id]);
      elseif($niv) $pdo->prepare("INSERT INTO expedientes(id_usuario,id_nivel) VALUES(?,?)")->execute([$id,$niv]);
      // Grupos: se sincronizan con lo marcado
      $sel=array_map('intval',$_POST['grupos']??[]);
      $act=$pdo->prepare("SELECT id_grupo FROM grupo_alumnos WHERE id_usuario=?"); $act->execute([$id]); $act=array_map('intval',$act->fetchAll(PDO::FETCH_COLUMN));
      foreach(array_diff($act,$sel) as $gid) $pdo->prepare("DELETE FROM grupo_alumnos WHERE id_usuario=? AND id_grupo=?")->execute([$id,$gid]);
      foreach(array_diff($sel,$act) as $gid) $pdo->prepare("INSERT INTO grupo_alumnos(id_grupo,id_usuario) VALUES(?,?)")->execute([$gid,$id]);
      $pdo->commit(); $msg='Datos del alumno actualizados.';
    }catch(PDOException $e){ if($pdo->inTransaction()) $pdo->rollBack(); $err='No se pudo actualizar: '.htmlspecialchars($e->getMessage()); }
  }
  if(($_POST['action']??'')==='crear'){
    try{ $pdo->prepare("INSERT INTO usuarios(nombre,apellido,email,password,telefono) VALUES(?,?,?,?,?)")->execute([trim($_POST['nombre']),trim($_POST['apellido']),trim($_POST['email']),password_hash($_POST['password'],PASSWORD_DEFAULT),trim($_POST['telefono'])?:null]);
      $msg='Alumno registrado con la matrícula AL-'.str_pad($pdo->lastInsertId(),4,'0',STR_PAD_LEFT); }
    catch(PDOException $e){ $err='No se pudo registrar (¿correo repetido?).'; }
  }
}
$q=trim($_GET['q']??''); $g=(int)($_GET['g']??0);
$sql="SELECT u.id_usuario,u.nombre,u.apellido,u.email,u.telefono,u.fecha_registro,e.id_nivel,n.nombre_nivel FROM usuarios u LEFT JOIN expedientes e ON e.id_usuario=u.id_usuario LEFT JOIN niveles n ON n.id_nivel=e.id_nivel WHERE (CONCAT(u.nombre,' ',u.apellido) LIKE :q1 OR u.email LIKE :q2 OR u.telefono LIKE :q3)";
$par=[':q1'=>"%$q%",':q2'=>"%$q%",':q3'=>"%$q%"];
if($g){$sql.=" AND EXISTS(SELECT 1 FROM grupo_alumnos x WHERE x.id_usuario=u.id_usuario AND x.id_grupo=:g)";$par[':g']=$g;}
$st=$pdo->prepare($sql." ORDER BY u.id_usuario DESC"); $st->execute($par); $alumnos=$st->fetchAll();
$gs=$pdo->query("SELECT g.id_grupo,g.nombre_grupo,c.nombre AS curso FROM grupos g LEFT JOIN cursos c ON c.id_curso=g.id_curso ORDER BY g.nombre_grupo")->fetchAll();
$niveles=$pdo->query("SELECT id_nivel,nombre_nivel FROM niveles ORDER BY id_nivel")->fetchAll();
$tags=[]; $gids=[]; foreach($pdo->query("SELECT ga.id_usuario,g.id_grupo,g.nombre_grupo FROM grupo_alumnos ga JOIN grupos g ON g.id_grupo=ga.id_grupo") as $r){ $tags[$r['id_usuario']][]=$r['nombre_grupo']; $gids[$r['id_usuario']][]=(int)$r['id_grupo']; }
require __DIR__.'/includes/header.php';
?>
<div class="actions" style="justify-content:space-between"><div><h1>Alumnos</h1><p class="muted">Consulta, registra, modifica y elimina alumnos. Cada alumno recibe una matrícula automática.</p></div>
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
<td style="white-space:nowrap"><button type="button" class="btn secondary small" onclick='editar(<?=htmlspecialchars(json_encode(['id'=>(int)$a['id_usuario'],'nombre'=>$a['nombre'],'apellido'=>$a['apellido'],'email'=>$a['email'],'telefono'=>$a['telefono']??'','nivel'=>(int)($a['id_nivel']??0),'grupos'=>$gids[$a['id_usuario']]??[],'mat'=>$mat],JSON_UNESCAPED_UNICODE),ENT_QUOTES)?>)'>Modificar</button> <button type="button" class="btn danger small" onclick="del(<?=$a['id_usuario']?>,<?=htmlspecialchars(json_encode($a['nombre'].' '.$a['apellido']))?>,'<?=$mat?>')">Eliminar</button></td></tr><?php endforeach; ?>
</tbody></table></div></section>
<dialog id="dlg"><form method="post"><h2>Eliminar alumno</h2><p id="dtxt" class="muted"></p>
<input type="hidden" name="action" value="eliminar"><input type="hidden" name="id" id="did">
<div class="actions"><button type="button" class="btn secondary" onclick="dlg.close()">Cancelar</button><button class="btn danger">Sí, eliminar</button></div></form></dialog>
<dialog id="nuevo"><form method="post"><h2>Nuevo alumno</h2><input type="hidden" name="action" value="crear">
<label>Nombre *</label><input name="nombre" required><label>Apellido *</label><input name="apellido" required><label>Correo *</label><input type="email" name="email" required>
<label>Teléfono</label><input name="telefono"><label>Contraseña *</label><input type="password" name="password" required>
<div class="actions" style="margin-top:14px"><button type="button" class="btn secondary" onclick="nuevo.close()">Cancelar</button><button class="btn">Registrar</button></div></form></dialog>
<dialog id="edit"><form method="post"><h2>Modificar alumno <small class="muted" id="emat"></small></h2><input type="hidden" name="action" value="editar"><input type="hidden" name="id" id="eid">
<label>Nombre *</label><input name="nombre" id="enombre" required><label>Apellido *</label><input name="apellido" id="eapellido" required><label>Correo *</label><input type="email" name="email" id="eemail" required>
<label>Teléfono</label><input name="telefono" id="etelefono"><label>Nivel</label><select name="nivel" id="enivel"><option value="0">Sin asignar</option><?php if(!$niveles): ?><option disabled>(No hay niveles en la tabla niveles)</option><?php endif; foreach($niveles as $n): ?><option value="<?=$n['id_nivel']?>"><?=htmlspecialchars($n['nombre_nivel'])?></option><?php endforeach; ?></select>
<label>Grupos (el curso depende del grupo)</label><div id="egrupos" style="max-height:150px;overflow:auto;border:1px solid #d8e0dc;border-radius:8px;padding:8px 10px;margin-bottom:4px"><?php foreach($gs as $x): ?><label style="display:flex;gap:8px;align-items:center;font-weight:400;margin:3px 0"><input type="checkbox" name="grupos[]" value="<?=$x['id_grupo']?>" style="width:auto;margin:0"> <?=htmlspecialchars($x['nombre_grupo'])?><?php if($x['curso']): ?> <small class="muted">· <?=htmlspecialchars($x['curso'])?></small><?php endif; ?></label><?php endforeach; if(!$gs): ?><small class="muted">No hay grupos creados.</small><?php endif; ?></div>
<label>Nueva contraseña <small class="muted">(déjala vacía para no cambiarla)</small></label><input type="password" name="password" autocomplete="new-password">
<div class="actions" style="margin-top:14px"><button type="button" class="btn secondary" onclick="edit.close()">Cancelar</button><button class="btn">Guardar cambios</button></div></form></dialog>
<script>function editar(a){eid.value=a.id;emat.textContent='('+a.mat+')';enombre.value=a.nombre;eapellido.value=a.apellido;eemail.value=a.email;etelefono.value=a.telefono;enivel.value=a.nivel;edit.querySelectorAll('#egrupos input').forEach(c=>c.checked=a.grupos.includes(+c.value));edit.querySelector('[name=password]').value='';edit.showModal()}
function del(i,n,m){did.value=i;dtxt.textContent='Vas a eliminar a '+n+' ('+m+'). También se borrarán su expediente, inscripciones y pagos. Esta acción no se puede deshacer.';dlg.showModal()}</script>
<?php require __DIR__.'/includes/footer.php'; ?>
