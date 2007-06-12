<?php do_header('Página no encontrada'); //output headers ?>

<h1>404 Página no encontrada</h1>

<div class="block"></div>

<p class="center error">La página que ingresaste no existe.
Posiblemente hayas ingresado incorrectamente la dirección o el enlace ya no existe.
Si crees que es un error por favor escribe a <a href="mailto:<?=$CFG->adminmail ?>">
Administración <?=$CFG->conference_name ?></a></p>

<?php do_submit_cancel('', 'Página principal', $CFG->wwwroot); // back button ?>

