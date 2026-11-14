<?php
define('PUBLICO',1);
require_once __DIR__.'/config/database.php';
$id=(int)($_GET['id'] ?? 0);
$st=$pdo->prepare("SELECT * FROM cursos WHERE id_curso=? AND estado='activo'"); $st->execute([$id]); $c=$st->fetch();
if(!$c){ header('Location: index.php#idiomas'); exit; }
$grupos=gruposDeCurso($pdo,$id);
$niveles=$pdo->query("SELECT * FROM niveles ORDER BY id_nivel")->fetchAll();
$otros=$pdo->prepare("SELECT id_curso,nombre FROM cursos WHERE estado='activo' AND id_curso<>? ORDER BY nombre"); $otros->execute([$id]); $otros=$otros->fetchAll();
[$cl,$sal]=idiomaInfo($c['nombre']); $def=defaultsCurso($c['nombre']); $al=alumnoActual();
$desc=trim((string)$c['descripcion']) ?: ($def['descripcion'] ?? 'Curso práctico con grupos reducidos, material incluido y acompañamiento de nuestros profesores.');
$lema=trim((string)($c['lema'] ?? '')) ?: ($def['lema'] ?? ''); $paraq=trim((string)($c['para_quien'] ?? '')) ?: ($def['para_quien'] ?? '');
$aprTxt=trim((string)($c['aprenderas'] ?? '')) ?: implode("\n",$def['aprenderas'] ?? []); $foto=trim((string)($c['foto_url'] ?? '')) ?: ($def['foto'] ?? ''); $titulo=$c['nombre'];
require __DIR__.'/includes/public_header.php';
?>
<section class="course-hero" style="background-color:#285c4d<?= $foto!=='' ? ';background-image:url(\''.htmlspecialchars($foto).'\')' : '' ?>"><div class="wrap">
  <div class="crumbs"><a href="index.php">Inicio</a> / <a href="index.php#idiomas">Idiomas</a></div>
  <span class="greet"><?= $sal ?></span><h1>Curso de <?= htmlspecialchars($c['nombre']) ?></h1><p><?= htmlspecialchars($lema) ?></p>
</div></section>
<section class="course-body"><div class="wrap grid">
  <div>
    <h2>Sobre el curso</h2><p><?= nl2br(htmlspecialchars($desc)) ?></p>
    <?php $apr=array_filter(array_map('trim',explode("\n",$aprTxt))); if($apr): ?><h3>Qué aprenderás</h3><ul class="check"><?php foreach($apr as $t): ?><li><?= htmlspecialchars($t) ?></li><?php endforeach; ?></ul><?php endif; ?>
    <?php if($paraq!==''): ?><h3>¿Para quién es?</h3><p><?= htmlspecialchars($paraq) ?></p><?php endif; ?>
    <?php if($niveles): ?><h3>Niveles</h3><div class="levels"><?php foreach($niveles as $i=>$n): ?><div class="level"><span class="num">Nivel <?= $i+1 ?></span><h4><?= htmlspecialchars($n['nombre_nivel']) ?></h4><p><?= htmlspecialchars((string)$n['descripcion']) ?></p></div><?php endforeach; ?></div><?php endif; ?>
    <h3>Horarios y modalidades</h3>
    <table class="sched"><thead><tr><th>Grupo</th><th>Horario</th><th>Modalidad</th><th>Disponibilidad</th></tr></thead><tbody>
    <?php foreach($grupos as $g): $lib=max(0,(int)$g['cupo']-(int)$g['ocup']); ?><tr><td><?= htmlspecialchars($g['nombre_grupo']) ?></td><td><?= htmlspecialchars($g['horario'] ?: '—') ?></td><td><?= modalidadTxt($g['modalidad']) ?></td><td><?= $lib>0 ? "<span class='pill ok'>$lib lugares</span>" : "<span class='pill warn'>Lista de espera</span>" ?></td></tr><?php endforeach; if(!$grupos): ?><tr><td colspan="4">Próximamente publicaremos grupos y horarios.</td></tr><?php endif; ?>
    </tbody></table>
  </div>
  <aside class="side-card"><h3>Empieza tu curso de <?= htmlspecialchars($c['nombre']) ?></h3>
    <dl><dt>Modalidad</dt><dd>Presencial y online</dd><dt>Duración</dt><dd><?= $c['duracion_horas'] ? (int)$c['duracion_horas'].' horas' : 'Por definir' ?></dd><dt>Nivel inicial</dt><dd>Te orientamos con una valoración de nivel</dd></dl>
    <a class="btn-green" href="#inscripcion" style="display:block">Inscribirme ↗</a>
    <small>Envía tu solicitud en un minuto. Servicios Escolares confirmará tu inscripción.</small>
  </aside>
</div></section>
<section class="enroll" id="inscripcion" style="padding-top:10px"><div class="wrap" style="max-width:860px">
  <div class="eyebrow">Inscríbete ahora</div><h2>Reserva tu lugar en <?= htmlspecialchars($c['nombre']) ?></h2>
  <div class="form-card"><form method="post" action="inscribirme.php?id=<?= $id ?>"><input type="hidden" name="id" value="<?= $id ?>">
    <div class="two">
      <div class="ff"><label>Nombre <span class="req">*</span></label><input name="nombre" value="<?= htmlspecialchars($al['nombre'] ?? '') ?>" placeholder="Tu nombre" required></div>
      <div class="ff"><label>Apellidos <span class="req">*</span></label><input name="apellidos" placeholder="Tus apellidos" required></div>
      <div class="ff"><label>Correo electrónico <span class="req">*</span></label><input type="email" name="correo" value="<?= htmlspecialchars($al['email'] ?? '') ?>" placeholder="tu@correo.com" required></div>
      <div class="ff"><label>Teléfono <span class="req">*</span></label><input type="tel" name="telefono" placeholder="Número de contacto" required></div>
    </div>
    <div class="ff"><label>Grupo, horario y modalidad <span class="req">*</span></label>
      <select name="grupo" required><option value="">Selecciona un grupo</option><?php foreach($grupos as $g): $lib=max(0,(int)$g['cupo']-(int)$g['ocup']); ?><option value="<?= $g['id_grupo'] ?>"><?= htmlspecialchars($g['nombre_grupo'].' · '.($g['horario'] ?: '—').' · '.modalidadTxt($g['modalidad']).($lib>0?' · '.$lib.' lugares':' · lista de espera')) ?></option><?php endforeach; ?></select>
      <div class="help">Si no sabes qué nivel te corresponde, elige el básico: te orientaremos con una valoración de nivel.</div></div>
    <?php if(!$grupos): ?><div class="notice warn show"><strong>Sin grupos publicados</strong>El administrador aún no ha creado grupos para este curso.</div><?php endif; ?>
    <div class="chk-row"><input type="checkbox" name="acepto" value="1" id="acepto2" required><label for="acepto2">He leído la política de privacidad y acepto que me contacten para confirmar mi inscripción. *</label></div>
    <button class="btn-green" type="submit">Enviar solicitud ↗</button>
  </form></div></div></section>
<section class="other-langs"><div class="wrap"><h2>Explora otros idiomas</h2><div class="row"><?php foreach($otros as $x): ?><a href="curso.php?id=<?= $x['id_curso'] ?>"><?= htmlspecialchars($x['nombre']) ?> →</a><?php endforeach; ?></div></div></section>
<?php require __DIR__.'/includes/public_footer.php'; ?>
