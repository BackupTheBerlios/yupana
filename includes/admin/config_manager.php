<?php 
// configuration manager
// just switch on/off conf flags
if (empty($CFG) || empty($q) || Context != 'admin') {
    die;
}

if ($USER->id_tadmin == 1) {
    require($CFG->admdir . 'config_optional_params.php');
}
?>

<h1>Configuración Yacomas</h1>

<?php
// show register open/close
require($CFG->admdir . 'config_reg_flags.php');

if ($USER->id_tadmin == 1) {
    // show system config values
    if (!empty($submit)) {
        require($CFG->admdir . 'config_optional_params_check.php');

        // update info if no errors
        if (empty($errmsg)) {
            require($CFG->admdir . 'config_update_info.php');
        }

    }
?>

<h1>Valores del Sistema</h1>

<?php
    if (!empty($errmsg)) {
        show_error($errmsg, false);
    }
?>

<form method="POST" action="">

    <h3>Información General</h3>

<?php
    require($CFG->admdir . 'config_input_general.php');
    do_submit_cancel('Guardar', '');
?>

    <h3>Configuración adicional</h3>

<?php
    require($CFG->admdir . 'config_input_system.php');
    do_submit_cancel('Guardar', '');
?>

</form>

<?php } else { ?>

<h2 class="error center">No se pudo obtener los valores de configuración. Consulte a su administrador</h2>

<?php } ?>
    
<div class="block"></div>

<?php
do_submit_cancel('', 'Volver al menu', $return_url);
?>
