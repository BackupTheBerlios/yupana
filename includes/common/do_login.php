<?php
if (empty($CFG)) {
    die;
}

if (!defined('Context')
    || (Context != 'admin'
        && Context != 'ponente'
        && Context != 'asistente')) {

    header('Location: ' . $CFG->wwwroot);
}

// messages holder
$errmsg = array();

require($CFG->comdir . 'signin.php');

// now we can start output content
do_header('Inicio de Sesión');

if (Context == 'admin') { ?>

<h1>Módulo de administración</h1>
<h2 class="center">Inicio de Sesión</h2> 

<?php } elseif (Context == 'ponente') { ?>

<h1>Inicio de Sesión Ponente</h1>

<?php } elseif (Context == 'asistente') { ?>

<h1>Inicio de Sesión Asistente</h1>

<?php
}

if (!empty($errmsg)) {
    show_error($errmsg);
} elseif ($exp == 'exp') {
    show_error('Su sesión ha caducado o no incio correctamente. Por favor trate de nuevo');
}

require($CFG->comdir . 'display_login_form.php');
?>
