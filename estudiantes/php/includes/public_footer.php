<footer class="site"><div class="wrap">
  <?php if(!empty($cta)): ?><div class="cta-row"><h2>El mundo tiene mucho que contarte.<br>¿Empezamos a hablar?</h2><a class="btn-light" href="index.php#idiomas">Encuentra tu idioma ↗</a></div><?php endif; ?>
  <div class="links"><a class="logo" href="index.php"><span class="mark">▭</span> Language Schoolar</a>
  <nav><a href="index.php#idiomas">Idiomas</a><a href="index.php#metodo">Nuestro método</a><a href="index.php#faq">Preguntas frecuentes</a>
  <?php if(esAdmin()): ?><a href="panel.php">Administración</a><?php endif; ?>
  <?php if(esAdmin() || alumnoActual()): ?><a href="logout.php">Cerrar sesión</a><?php else: ?><a href="index.php?login=1">Mi cuenta</a><a href="admin.php">Acceso administración</a><?php endif; ?></nav></div>
  <div class="legal"><span>© 2026 Language Schoolar. Aprender nos acerca.</span></div>
</div></footer>
</body></html>
