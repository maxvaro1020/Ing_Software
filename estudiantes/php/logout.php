<?php
require_once __DIR__.'/config/sesion.php';
$_SESSION=[]; session_destroy();
header('Location: index.php');
