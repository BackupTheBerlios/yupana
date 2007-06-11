<?php
require('includes/lib.php');

$context = optional_param('context');

if (empty($context)
    || ($context != 'admin'
        && $context != 'ponente'
        && $context != 'asistente')) {

    header('Location: ' . $CFG->wwwroot);
}

define('Context', $context);

// messages holder
$errmsg = array();

require($CFG->incdir . 'common/signin.php');

// stat content
do_header();

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

require($CFG->incdir . 'common/display_login_form.php');
do_footer();
?>
