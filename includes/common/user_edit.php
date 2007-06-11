<?php
if (!defined('Context') || empty($CFG)
    || (Context != 'admin'
        && Context != 'ponente'
        && Context != 'asistente')) {

    header('Location: ' . $CFG->wwwroot);
}

require($CFG->incdir . 'common/user_optional_params.php');

switch (Context) {
    case 'admin':
        $name = 'administrador';
        $home_url = $CFG->wwwroot . '/admin/';
        break;

    case 'ponente':
        $name = 'ponente';
        $home_url = $CFG->wwwroot . '/ponente/';
        break;

    case 'asistente':
        $name = 'asistente';
        $home_url = $CFG->wwwroot . '/asistente/';
        break;
}

?>

<h1>Modificar datos de <?=$name ?></h1>

<?php
// process submit
if (!empty($submit)) {
    // messages holder
    $errmsg = array();

    require($CFG->incdir . 'common/user_optional_params_check.php');

    if (!empty($errmsg)) {
        show_error($errmsg);
    } else {
        // update user
        require($CFG->incdir . 'common/user_update_info.php');

        do_submit_cancel('', 'Volver al Menu', $home_url);
    }
}

if (empty($submit) || !empty($errmsg)) { // show form
?> 

<form method="POST" action="">

    <?php if (Context == 'ponente' || Context == 'asistente') { ?>

    <p class="error center">Asegúrate de escribir bien tus datos ya que estos serán
    tomados para tu constancia de participación</p>

    <?php } ?>

    <p class="center"><em>Los campos marcados con asterisco(*) son obligatorios</em></p>

    <?php
    include($CFG->incdir . 'common/display_user_info_input_table.php');
    do_submit_cancel('Actualizar', 'Cancelar', $home_url);
    ?>

</form>

<?php
}
?>

