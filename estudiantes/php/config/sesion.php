<?php
if (session_status() === PHP_SESSION_NONE) session_start();
// Acceso de administración (cámbialo aquí):
const ADMIN_EMAIL = 'admin@languageschoolar.com';
const ADMIN_PASS  = 'admin123';
// Acceso de Servicios Financieros (rol separado del Administrador; cámbialo aquí):
const FIN_EMAIL = 'finanzas@languageschoolar.com';
const FIN_PASS  = 'finanzas123';
function esAdmin(){ return !empty($_SESSION['admin']); }
function esFinanzas(){ return !empty($_SESSION['finanzas']); }
function alumnoActual(){ return $_SESSION['alumno'] ?? null; }
function baseUrl(){ $root=rtrim(str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']),'/'); return str_replace($root,'',str_replace('\\','/',dirname(__DIR__))); }
function requireAdmin(){ if(!esAdmin()){ header('Location: '.baseUrl().'/admin.php'); exit; } }
function requireFinanzas(){ if(!esFinanzas()){ header('Location: '.baseUrl().'/finanzas/index.php'); exit; } }
function nextSeguro($n){ return preg_match('/^[a-z\-]+\.php(\?[a-z0-9=&]*)?$/i',(string)$n) ? $n : 'index.php#idiomas'; }
function pillEstado($e){ $m=['pendiente'=>['warn','Pendiente'],'confirmada'=>['ok','Confirmada'],'lista_espera'=>['warn','Lista de espera'],'cancelada'=>['bad','Cancelada'],'aprobado'=>['ok','Aprobado'],'rechazado'=>['bad','Rechazado']]; [$c,$t]=$m[$e]??['',$e]; return "<span class='pill $c'".($c==='bad'?" style='background:#fde8e8;color:#9b1c1c'":'').">$t</span>"; }
// Estado de pago de una inscripción, derivado de los pagos que valida Servicios Financieros.
// Administración / Servicios Escolares solo consulta este valor (nunca montos ni comprobantes).
// Valores: pagado | en_revision | rechazado | sin_pago
function sqlEstadoPago($alias='i'){
  return "CASE WHEN EXISTS(SELECT 1 FROM pagos p WHERE p.id_inscripcion=$alias.id_inscripcion AND p.estado_pago='aprobado') THEN 'pagado'
    WHEN EXISTS(SELECT 1 FROM pagos p WHERE p.id_inscripcion=$alias.id_inscripcion AND p.estado_pago='pendiente') THEN 'en_revision'
    WHEN EXISTS(SELECT 1 FROM pagos p WHERE p.id_inscripcion=$alias.id_inscripcion AND p.estado_pago='rechazado') THEN 'rechazado'
    ELSE 'sin_pago' END"; }
function estadoPagoInscripcion($pdo,$id){ $st=$pdo->prepare("SELECT ".sqlEstadoPago('i')." FROM inscripciones i WHERE i.id_inscripcion=?"); $st->execute([(int)$id]); return $st->fetchColumn() ?: 'sin_pago'; }
function pagoTxt($e){ return ['pagado'=>'Pagado','en_revision'=>'En revisión','rechazado'=>'Rechazado','sin_pago'=>'Sin pago'][$e]??$e; }
function idiomaInfo($n){ $k=strtr(mb_strtolower($n),['í'=>'i','é'=>'e','á'=>'a','ñ'=>'n']);
  foreach(['ingl'=>['en','Hello!'],'franc'=>['fr','Bonjour!'],'ital'=>['it','Ciao!'],'alem'=>['de','Hallo!']] as $p=>$v) if(str_contains($k,$p)) return $v; return ['','¡Hola!']; }
function modalidadTxt($m){ return ['presencial'=>'Presencial','en_linea'=>'Online en directo','mixta'=>'Mixta'][$m]??$m; }

function defaultsCurso($nombre){ static $D=null; if($D===null) $D=json_decode(<<<'JSONDATA'
{"ingl":{"lema":"Para viajar, trabajar y compartir tus ideas sin fronteras.","descripcion":"El inglés es la puerta más amplia para estudiar, trabajar y viajar. En nuestro curso practicas desde la primera clase con situaciones reales: presentarte, pedir información, participar en una reunión o escribir un correo. Los grupos son pequeños para que hables mucho y recibas corrección personal.","aprenderas":["Presentarte y mantener conversaciones cotidianas","Entender y responder en llamadas, viajes y trámites","Escribir correos y mensajes claros","Mejorar pronunciación y comprensión auditiva","Prepararte para certificaciones (opcional)"],"para_quien":"Jóvenes y adultos, desde quienes empiezan de cero hasta quienes quieren perfeccionar su inglés profesional.","foto":"https://images.unsplash.com/photo-1687862802690-10c1f32d7b1f?auto=format&fit=crop&w=1800&q=75"},"franc":{"lema":"Conecta con nuevas culturas, una conversación a la vez.","descripcion":"Aprende francés de forma natural y divertida: pronunciación, expresiones del día a día y cultura de los países francófonos. Ideal para viajar, estudiar o simplemente disfrutar de un idioma lleno de matices. Cada clase combina conversación, escucha y juegos de rol.","aprenderas":["Saludar, pedir y moverte en situaciones cotidianas","Pronunciar con confianza los sonidos del francés","Entender textos y audios sencillos","Conocer cultura y costumbres francófonas","Preparar niveles base de certificación (opcional)"],"para_quien":"Cualquier persona con curiosidad por el idioma; no necesitas conocimientos previos para empezar.","foto":"https://images.unsplash.com/photo-1749867817713-4dc76f47dca4?auto=format&fit=crop&w=1800&q=75"},"ital":{"lema":"Descubre un idioma lleno de vida, arte y encuentros.","descripcion":"El italiano se aprende rápido y se disfruta aún más. Trabajamos conversación, música, cine y gastronomía para que avances con motivación. Perfecto para viajeros, amantes del arte o quienes tienen raíces italianas.","aprenderas":["Conversar en restaurantes, viajes y encuentros sociales","Entender el ritmo y la entonación italianos","Leer textos breves y comprender canciones y películas","Usar los verbos más frecuentes con seguridad","Descubrir expresiones y cultura de Italia"],"para_quien":"Principiantes y estudiantes con base de otros idiomas romances que quieren avanzar rápido.","foto":"https://images.unsplash.com/photo-1761824615173-5f3c622bf7b7?auto=format&fit=crop&w=1800&q=75"},"alem":{"lema":"Abre nuevas puertas en tu vida personal y profesional.","descripcion":"El alemán abre oportunidades académicas y laborales en Europa. Con una metodología clara y progresiva, dominas la estructura del idioma sin perder la conversación: gramática explicada de forma sencilla, práctica constante y vocabulario útil desde el inicio.","aprenderas":["Comunicarte en situaciones de estudio, trabajo y viaje","Entender la estructura de la oración alemana","Ampliar vocabulario profesional y cotidiano","Redactar textos y correos formales","Prepararte para exámenes de nivel (opcional)"],"para_quien":"Estudiantes y profesionales que buscan becas, intercambio o empleo en países de habla alemana.","foto":"https://images.unsplash.com/photo-1780049921469-4afa2c328d4e?auto=format&fit=crop&w=1800&q=75"}}
JSONDATA
,true);
  $k=strtr(mb_strtolower($nombre),['í'=>'i','é'=>'e','á'=>'a','ñ'=>'n']);
  foreach($D as $p=>$v) if(str_contains($k,$p)) return $v; return []; }
function gruposDeCurso($pdo,$id){
  $sql="SELECT g.*,(SELECT COUNT(*) FROM inscripciones i WHERE i.id_grupo=g.id_grupo AND i.estado IN('pendiente','confirmada')) ocup FROM grupos g WHERE g.estado='activo' AND g.id_curso";
  $st=$pdo->prepare($sql.'=? ORDER BY g.nombre_grupo'); $st->execute([$id]); $r=$st->fetchAll();
  if(!$r) $r=$pdo->query($sql.' IS NULL ORDER BY g.nombre_grupo')->fetchAll();
  return $r; }
