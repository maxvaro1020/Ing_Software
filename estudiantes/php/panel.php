<?php
require_once __DIR__.'/config/database.php';
$pageTitle='Panel';
$n=fn($sql)=>(int)$pdo->query($sql)->fetchColumn();
$stats=[[$n("SELECT COUNT(*) FROM usuarios"),'Alumnos registrados'],[$n("SELECT COUNT(*) FROM grupos WHERE estado='activo'"),'Grupos activos'],
[$n("SELECT COUNT(*) FROM inscripciones WHERE estado='pendiente'"),'Inscripciones pendientes'],[$n("SELECT COUNT(*) FROM inscripciones WHERE estado='lista_espera'"),'En lista de espera'],
[$n("SELECT COUNT(*) FROM pagos WHERE estado_pago='pendiente'"),'Pagos por validar']];
$grupos=$pdo->query("SELECT g.*,COALESCE(SUM(i.estado='confirmada'),0) c,COALESCE(SUM(i.estado='pendiente'),0) p,COALESCE(SUM(i.estado='lista_espera'),0) e FROM grupos g LEFT JOIN inscripciones i ON i.id_grupo=g.id_grupo GROUP BY g.id_grupo ORDER BY g.nombre_grupo")->fetchAll();
$mod=['presencial'=>'Presencial','en_linea'=>'En línea','mixta'=>'Mixta'];
require __DIR__.'/includes/header.php';
?>
<h1>Panel</h1><p class="muted">Hola, Administrador. Este es el resumen de hoy.</p>
<div class="stats"><?php foreach($stats as [$v,$l]): ?><div class="stat"><b><?=$v?></b><span><?=$l?></span></div><?php endforeach; ?></div>
<section class="card"><div class="actions" style="justify-content:space-between"><h2>Alumnos por grupo</h2><a class="btn secondary small" href="grupos/index.php">Ver detalle</a></div>
<div class="table-wrap"><table><thead><tr><th>Grupo</th><th>Horario</th><th>Modalidad</th><th>Confirmados</th><th>Pendientes</th><th>En espera</th><th>Total</th><th>Ocupación</th></tr></thead><tbody>
<?php foreach($grupos as $g): $t=$g['c']+$g['p']; $cu=max(1,(int)$g['cupo']); $pc=min(100,$t*100/$cu); ?><tr>
<td><b><?=htmlspecialchars($g['nombre_grupo'])?></b></td><td><?=htmlspecialchars($g['horario']?:'—')?></td><td><?=$mod[$g['modalidad']]??''?></td>
<td><?=$g['c']?></td><td><?=$g['p']?></td><td><?=$g['e']?></td><td><b><?=$t?></b></td>
<td><div class="bar <?=$pc>=100?'full':''?>"><i style="width:<?=$pc?>%"></i></div><small class="muted"><?=$t?> / <?=$cu?></small></td></tr><?php endforeach; if(!$grupos): ?><tr><td colspan="8" class="muted">Aún no hay grupos.</td></tr><?php endif; ?>
</tbody></table></div></section>
<?php require __DIR__.'/includes/footer.php'; ?>
