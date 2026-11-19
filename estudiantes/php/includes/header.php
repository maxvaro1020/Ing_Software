<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$root = rtrim(str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']),'/');
$base = str_replace($root,'',str_replace('\\','/',dirname(__DIR__))); // URL de /php
$css  = dirname($base).'/css/admin.css';
$self = str_replace('\\','/',$_SERVER['SCRIPT_NAME']);
$items = [
 'GENERAL'=>[['Panel','panel.php','panel.php'],],
 'GESTIÓN'=>[['Alumnos','alumnos.php','alumnos.php'],['Cursos','cursos.php','cursos.php'],['Grupos','grupos/index.php','/grupos/'],['Inscripciones','inscripciones.php','inscripciones.php'],['Preguntas frecuentes','faqs.php','faqs.php'],['Generador de listas','listas/index.php','/listas/']],
 'SITIO'=>[['↗ Página principal','index.php','index.php?x']],
];
if(!function_exists('tagEstado')){function tagEstado($e){$m=['pendiente'=>['warn','Pendiente'],'confirmada'=>['','Confirmada'],'cancelada'=>['err','Cancelada'],'lista_espera'=>['info','Lista de espera'],'aprobado'=>['','Aprobado'],'rechazado'=>['err','Rechazado']];[$c,$t]=$m[$e]??['',$e];return "<span class='tag $c'>$t</span>";}}
if(!function_exists('tagPago')){function tagPago($e){$c=['pagado'=>'','en_revision'=>'warn','rechazado'=>'err','sin_pago'=>'info'][$e]??'';return "<span class='tag $c'>".pagoTxt($e)."</span>";}}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($pageTitle ?? 'Administración') ?> · Language Schoolar</title>
<link rel="stylesheet" href="<?= $css ?>">
</head>
<body>
<header class="topbar">
  <a class="brand" href="<?= $base ?>/panel.php">Language Schoolar</a>
  <div class="user"><span>Administrador Sistema<small>Administrador</small></span><b class="avatar">AS</b><a class="out" href="<?= $base ?>/index.php">↗ Ver sitio</a><a class="out" href="<?= $base ?>/logout.php">Cerrar sesión</a></div>
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
