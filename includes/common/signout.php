<?php

    if (empty($type)) {
        die;
    }

    require_once(dirname(__FILE__). '/../lib.php');

    // check current session
    beginSession($type);

    // end session
    session_unset();
    session_destroy();

    // show page
    do_header();

    if ($type == 'R') {
        $who = 'Administrador';
    } elseif ($type == 'P') {
        $who = 'Ponente';
    } elseif ($type == 'A') {
        $who = 'Asistente';
    }
?>

<h1>Salida de sesión <?=$who ?></h1>

<div class="block"></div>

<p class="center">Ha salido exitosamente del sistema.</p>

<?php
    do_submit_cancel('', 'Regresar', get_url());
    do_footer();
?>
