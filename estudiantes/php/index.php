<?php
define('PUBLICO',1);
require_once __DIR__.'/config/database.php';
$loginErr=''; $regErr=''; $askOk=false; $askBad=false; $abrir=isset($_GET['login']); $pane=isset($_GET['reg'])?'reg':'login';
$next=nextSeguro($_GET['next'] ?? $_POST['next'] ?? '');
if($_SERVER['REQUEST_METHOD']==='POST'){
  $a=$_POST['action'] ?? '';
  if($a==='login'){
    $abrir=true; $em=trim($_POST['email'] ?? ''); $pw=$_POST['password'] ?? '';
    if(strcasecmp($em,ADMIN_EMAIL)===0 && $pw===ADMIN_PASS){ $_SESSION['admin']=true; header('Location: panel.php'); exit; }
    if(strcasecmp($em,FIN_EMAIL)===0 && $pw===FIN_PASS){ $_SESSION['finanzas']=true; header('Location: finanzas/panel.php'); exit; }
    $st=$pdo->prepare("SELECT id_usuario,nombre,email,password FROM usuarios WHERE email=?"); $st->execute([$em]); $u=$st->fetch();
    if($u && (password_verify($pw,$u['password']) || hash_equals((string)$u['password'],$pw))){
      $_SESSION['alumno']=['id'=>$u['id_usuario'],'nombre'=>$u['nombre'],'email'=>$u['email']]; header('Location: '.$next); exit; }
    $loginErr='Correo o contraseña incorrectos.';
  }
  if($a==='registro'){
    $abrir=true; $pane='reg';
    $n=trim($_POST['nombre'] ?? ''); $ap=trim($_POST['apellido'] ?? ''); $em=trim($_POST['email'] ?? ''); $pw=$_POST['password'] ?? '';
    if($n===''||$ap===''||!filter_var($em,FILTER_VALIDATE_EMAIL)||strlen($pw)<4) $regErr='Completa todos los campos (contraseña de al menos 4 caracteres).';
    else{ try{
      $pdo->prepare("INSERT INTO usuarios(nombre,apellido,email,password) VALUES(?,?,?,?)")->execute([$n,$ap,$em,password_hash($pw,PASSWORD_DEFAULT)]);
      $_SESSION['alumno']=['id'=>$pdo->lastInsertId(),'nombre'=>$n,'email'=>$em]; header('Location: '.$next); exit;
    }catch(PDOException $e){ $regErr='Ese correo ya está registrado. Inicia sesión.'; } }
  }
  if($a==='pregunta'){
    $q=trim($_POST['pregunta'] ?? ''); $em=trim($_POST['correo'] ?? '');
    if($q!=='' && filter_var($em,FILTER_VALIDATE_EMAIL) && !empty($_POST['acepto'])){
      $pdo->prepare("INSERT INTO faqs(pregunta,respuesta,categoria) VALUES(?,'','pendiente')")->execute([$q.' [contacto: '.$em.']']); $askOk=true;
    } else $askBad=true;
  }
}
$cursos=$pdo->query("SELECT * FROM cursos WHERE estado='activo' ORDER BY nombre")->fetchAll();
$faqs=$pdo->query("SELECT * FROM faqs WHERE COALESCE(categoria,'')<>'pendiente' AND respuesta<>'' ORDER BY id_faq")->fetchAll();
$titulo='Aprende idiomas'; $esIndex=true; $cta=true;
require __DIR__.'/includes/public_header.php';
?>
  <section class="hero">
    <div class="wrap">
      <div>
        <div class="eyebrow">• Idiomas para conectar con el mundo</div>
        <h1>Tu próxima conversación <em>empieza aquí.</em></h1>
        <p class="lead">Aprende un idioma y abre espacio a nuevas historias. En Language Schoolar te acompañamos para que te expreses con confianza, a tu ritmo.</p>
        <div class="cta">
          <a class="btn-green" href="#idiomas">Explorar idiomas ↗</a>
          <a class="btn-ghost" href="#metodo">Conoce nuestro método</a>
        </div>
        <small class="note">Desde tu primer «hola» hasta tu próxima gran idea.</small>
      </div>
      <div class="hero-visual">
        <div class="bubble"><strong>Menos miedo. Más conversación.</strong><span>Un lugar para aprender y equivocarte.</span></div>
      </div>
    </div>

    <aside class="login-panel" id="loginPanel" style="display:<?= $abrir?'block':'none' ?>">
      <div class="tabs"><button class="<?= $pane==='login'?'on':'' ?>" data-pane="paneLogin">Iniciar sesión</button><button class="<?= $pane==='reg'?'on':'' ?>" data-pane="paneCrear">Crear cuenta</button></div>
      <div class="pane <?= $pane==='login'?'on':'' ?>" id="paneLogin">
        <h3>Qué bueno verte.</h3><p class="sub">Entra y continúa donde lo dejaste.</p>
        <form method="post"><input type="hidden" name="action" value="login"><input type="hidden" name="next" value="<?= htmlspecialchars($next) ?>">
          <label>Correo electrónico</label><input type="email" name="email" placeholder="tu@correo.com" required>
          <label>Contraseña</label><input type="password" name="password" placeholder="Escribe tu contraseña" required>
          <button class="btn-green" type="submit" style="margin-top:14px">Iniciar sesión</button>
          <?php if($loginErr): ?><div class="err show"><?= $loginErr ?></div><?php endif; ?>
        </form>
        <p class="foot">¿Es tu primera vez? <a href="#" onclick="document.querySelector('[data-pane=paneCrear]').click();return false;">Regístrate aquí</a></p>
      </div>
      <div class="pane <?= $pane==='reg'?'on':'' ?>" id="paneCrear">
        <h3>Crea tu cuenta</h3><p class="sub">Regístrate para inscribirte en un curso.</p>
        <form method="post"><input type="hidden" name="action" value="registro"><input type="hidden" name="next" value="<?= htmlspecialchars($next) ?>">
          <label>Nombre</label><input type="text" name="nombre" required><label>Apellido</label><input type="text" name="apellido" required>
          <label>Correo electrónico</label><input type="email" name="email" required><label>Contraseña</label><input type="password" name="password" required>
          <button class="btn-green" type="submit" style="margin-top:14px">Crear cuenta</button>
          <?php if($regErr): ?><div class="err show"><?= $regErr ?></div><?php endif; ?>
        </form>
        <p class="foot">Si ya tienes cuenta, <a href="#" onclick="document.querySelector('[data-pane=paneLogin]').click();return false;">inicia sesión</a></p>
      </div>
    </aside>
  </section>

  <div class="values">
    <div class="wrap">
      <div><h4>Habla desde el primer día</h4><p>El idioma se aprende usándolo.</p></div>
      <div><h4>Encuentra tu ritmo</h4><p>Clases presenciales y online.</p></div>
      <div><h4>Crece con acompañamiento</h4><p>Un camino que parte de tu nivel.</p></div>
    </div>
  </div>

  <section class="langs" id="idiomas">
    <div class="wrap">
      <div class="head">
        <div><div class="eyebrow">Elige tu próximo idioma</div><h2>Un idioma. Muchas posibilidades.</h2></div>
        <p>¿Aún no sabes por dónde empezar? Te ayudamos a encontrar tu nivel y tu curso.</p>
      </div>
      <div class="cards">
<?php foreach($cursos as $c): [$cl,$sal]=idiomaInfo($c['nombre']); ?>
        <article class="lang-card"><div class="img <?= $cl ?>"><span><?= $sal ?></span></div><div class="body"><h3><?= htmlspecialchars($c['nombre']) ?></h3><p><?= htmlspecialchars(mb_strimwidth((string)$c['descripcion'],0,120,'…')) ?></p><div class="meta">▭ <?= $c['duracion_horas'] ? (int)$c['duracion_horas'].' horas' : 'Varios niveles' ?> · Presencial y online</div><a href="curso.php?id=<?= $c['id_curso'] ?>">Conocer el curso <span>↗</span></a></div></article>
<?php endforeach; if(!$cursos): ?><p>Pronto publicaremos nuestros cursos.</p><?php endif; ?>
      </div>
    </div>
  </section>

  <section class="method" id="metodo">
    <div class="wrap">
      <div class="pic"><span>♡ Aquí, equivocarse también es aprender.</span></div>
      <div>
        <div class="eyebrow">Así aprendemos en Language Schoolar</div>
        <h2>Aprender es atreverse. Nosotros te acompañamos.</h2>
        <div class="step"><div class="n">01</div><div><h4>Empezamos contigo</h4><p>Nos cuentas tus objetivos y te orientamos para encontrar el nivel y la modalidad que encajan contigo.</p></div></div>
        <div class="step"><div class="n">02</div><div><h4>Lo llevamos a la vida real</h4><p>Conversaciones, situaciones cotidianas y actividades prácticas. Aprendes haciendo, no solo memorizando.</p></div></div>
        <div class="step"><div class="n">03</div><div><h4>Avanzamos a tu lado</h4><p>Resuelve tus dudas, recibe orientación y reconoce lo que ya puedes hacer en tu nuevo idioma.</p></div></div>
      </div>
    </div>
  </section>

  <section class="faq" id="faq">
    <div class="wrap">
      <div class="eyebrow">Preguntas frecuentes</div>
      <h2>Toda gran conversación empieza con una pregunta.</h2>
      <div class="cols">
        <div>
<?php foreach($faqs as $i=>$f): ?>
          <details class="q" <?= $i<4?'open':'' ?>><summary><?= htmlspecialchars($f['pregunta']) ?></summary><p><?= nl2br(htmlspecialchars($f['respuesta'])) ?></p></details>
<?php endforeach; if(!$faqs): ?><p>Aún no hay preguntas publicadas.</p><?php endif; ?>
        </div>
        <form class="ask" method="post" action="index.php#faq"><input type="hidden" name="action" value="pregunta">
          <h3>¿Te queda alguna duda?</h3><p class="sub">Añade tu pregunta y te responderemos por correo. Estamos aquí para ayudarte.</p>
          <label>Correo electrónico *</label><input type="email" name="correo" placeholder="tu@correo.com" required>
          <label>Tu pregunta *</label><textarea name="pregunta" placeholder="¿Qué te gustaría saber sobre nuestros cursos?" required></textarea>
          <div class="chk"><input type="checkbox" name="acepto" value="1"><span>He leído la política de privacidad y acepto que me contacten para resolver mi consulta. *</span></div>
          <button class="btn-green" type="submit">Enviar pregunta ↗</button>
          <?php if($askOk): ?><div class="ok show">Pregunta enviada. Te responderemos por correo.</div><?php endif; if($askBad): ?><div class="bad show">Completa los campos obligatorios (*) y acepta la política de privacidad.</div><?php endif; ?>
        </form>
      </div>
    </div>
  </section>

<script>
document.querySelectorAll('.tabs button').forEach(b=>b.addEventListener('click',()=>{document.querySelectorAll('.tabs button').forEach(x=>x.classList.remove('on'));document.querySelectorAll('.pane').forEach(x=>x.classList.remove('on'));b.classList.add('on');document.getElementById(b.dataset.pane).classList.add('on');}));
var ab=document.getElementById('accountBtn'),lp=document.getElementById('loginPanel');
if(ab&&lp)ab.addEventListener('click',()=>{lp.style.display=lp.style.display==='none'?'block':'none';});
</script>
<?php require __DIR__.'/includes/public_footer.php'; ?>
