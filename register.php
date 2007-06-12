<?php
require('includes/lib.php');

$context = optional_param('context');

if (empty($context)
    || ($context != 'ponente'
        && $context != 'asistente')) {

    header('Location: ' . $CFG->wwwroot);
}

// return url
$home_url = $CFG->wwwroot

define('Context', $context);
require($CFG->incdir . 'common/user_optional_params.php');

if (Context == 'ponente') {
    $name = 'Ponentes';
    $dbtable = 'ponente';
}

if (Context == 'asistente') {
    $name = 'Asistentes';
    $dbtable = 'asistente';
}


// start page
do_header();
?>

<h1>Registro de <?=$name ?></h1>

<?php
require($CFG->incdir . 'common/register_flag_check.php');

// Process submit
if (!empty($submit)) {
    // errors holder
    $errmsg = array();

    require($CFG->incdir . 'common/user_optional_params_check.php');

    if (!empty($errmsg)) {
        show_error($errmsg);
    } else {
        // insert user & show updated info
        require($CFG->incdir . 'common/user_update_info.php');

        do_submit_cancel('', 'Continuar', $home_url);
        do_footer();
        exit; //END
    }
}

// Show form
?>

<form method="POST" action="">
    <p class="error center">Asegúrate de escribir bien tus datos personales ya que estos serán tomados para tu constancia de participación</p>
    <p class="center"><em>Los campos marcados con(*) son obligatorios</em></p>

<?php
include($CFG->incdir . 'common/display_user_info_input_table.php');
do_submit_cancel('Registrarme', 'Cancelar', $CFG->wwwroot);
?>

</form>

<?php
do_footer();
?>
