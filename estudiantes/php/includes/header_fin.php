<?php
// Encabezado de la interfaz de Servicios Financieros (rol separado del Administrador).
if (session_status() === PHP_SESSION_NONE) session_start();
$root = rtrim(str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']),'/');
$base = str_replace($root,'',str_replace('\\','/',dirname(__DIR__))); // URL de /php
$css  = dirname($base).'/css/admin.css';
$self = str_replace('\\','/',$_SERVER['SCRIPT_NAME']);
$items = [
 'GENERAL'=>[['Panel financiero','finanzas/panel.php','finanzas/panel.php']],
 'COBRANZA'=>[['Pagos','finanzas/pagos.php','finanzas/pagos.php']],
];
if(!function_exists('tagEstado')){function tagEstado($e){$m=['pendiente'=>['warn','Pendiente'],'aprobado'=>['','Aprobado'],'rechazado'=>['err','Rechazado']];[$c,$t]=$m[$e]??['',$e];return "<span class='tag $c'>$t</span>";}}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($pageTitle ?? 'Servicios Financieros') ?> · Servicios Financieros · Language Schoolar</title>
<link rel="stylesheet" href="<?= $css ?>">
</head>
<body>
<header class="topbar">
  <a class="brand" href="<?= $base ?>/finanzas/panel.php">Language Schoolar</a>
  <div class="user"><span>Servicios Financieros<small>Caja</small></span><b class="avatar">SF</b><a class="out" href="<?= $base ?>/logout.php">Cerrar sesión</a></div>
</header>
<div class="layout">
<aside class="sidebar">
<?php foreach ($items as $sec => $links): ?>
  <p class="sec"><?= $sec ?></p>
  <?php foreach ($links as [$label,$href,$match]): $on = str_contains($self,$match); ?>
    <a href="<?= $base.'/'.$href ?>" class="<?= $on?'active':'' ?>"><?= $label ?></a>
  <?php endforeach; ?>
<?php endforeach; ?>
</aside>
<main class="content">
