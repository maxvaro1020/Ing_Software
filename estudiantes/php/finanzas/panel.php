<?php
define('FINANZAS',1);
require_once __DIR__.'/../config/database.php';
$pageTitle='Panel financiero';
$q=fn($sql)=>$pdo->query($sql)->fetchColumn();
$stats=[
 [(int)$q("SELECT COUNT(*) FROM pagos WHERE estado_pago='pendiente'"),'Pagos por validar'],
 [(int)$q("SELECT COUNT(*) FROM pagos WHERE estado_pago='aprobado' AND DATE(fecha_validacion)=CURDATE()"),'Validados hoy'],
 [(int)$q("SELECT COUNT(*) FROM pagos WHERE estado_pago='rechazado'"),'Rechazados'],
 ['$'.number_format((float)$q("SELECT COALESCE(SUM(monto),0) FROM pagos WHERE estado_pago='aprobado'"),2),'Monto validado'],
];
$ult=$pdo->query("SELECT p.*,u.nombre,u.apellido,g.nombre_grupo FROM pagos p JOIN inscripciones i ON i.id_inscripcion=p.id_inscripcion JOIN usuarios u ON u.id_usuario=i.id_usuario LEFT JOIN grupos g ON g.id_grupo=i.id_grupo WHERE p.estado_pago='pendiente' ORDER BY p.id_pago ASC LIMIT 10")->fetchAll();
require __DIR__.'/../includes/header_fin.php';
?>
<h1>Panel financiero</h1><p class="muted">Resumen de pagos reportados por los alumnos.</p>
<div class="stats"><?php foreach($stats as [$v,$l]): ?><div class="stat"><b><?=$v?></b><span><?=$l?></span></div><?php endforeach; ?></div>
<section class="card"><div class="actions" style="justify-content:space-between"><h2>Pagos pendientes más antiguos</h2><a class="btn secondary small" href="pagos.php">Ir a pagos</a></div>
<div class="table-wrap"><table><thead><tr><th>Folio</th><th>Alumno</th><th>Grupo</th><th>Monto</th><th>Fecha</th><th></th></tr></thead><tbody>
<?php foreach($ult as $p): ?><tr><td><b>PG-<?=str_pad($p['id_pago'],4,'0',STR_PAD_LEFT)?></b></td><td><?=htmlspecialchars($p['nombre'].' '.$p['apellido'])?></td><td><?=htmlspecialchars($p['nombre_grupo']??'—')?></td><td>$<?=number_format($p['monto'],2)?></td><td><?=date('d/m/Y',strtotime($p['fecha_pago']))?></td><td><a class="btn small" href="pagos.php?id=<?=$p['id_pago']?>">Revisar</a></td></tr><?php endforeach; if(!$ult): ?><tr><td colspan="6" class="muted">No hay pagos por validar.</td></tr><?php endif; ?>
</tbody></table></div></section>
<?php require __DIR__.'/../includes/footer.php'; ?>
