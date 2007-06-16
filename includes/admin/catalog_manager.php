<?php 
// configuration manager
// just switch on/off conf flags
if (empty($CFG) || empty($q) || Context != 'admin' || $USER->id_tadmin != 1) {
    die;
}

$catalogs = array(
    'tadmin' => 'Tipos de administradores',
    'tasistente' => 'Tipos de asistentes',
    'estado' => 'Estado/Procedencia',
    'estudios' => 'Estudios',
    'prop_tipo' => 'Tipos de propuestas',
    'orientacion' => 'Orientación de propuestas',
    'prop_nivel' => 'Niveles de propuestas',
    'prop_status' => 'Estados de propuestas'
    );

?>

<h1>Administrar Catalogos</h1>

<form method="POST" action="">

<?php
foreach ($catalogs as $catalog => $desc) {
?>

<h2><?=$desc ?></h2>

<?php
    // restet messages
    $errmsg = array();

    $submit = optional_param('submit-'.$catalog);

    // show system config values
    if (!empty($submit)) {
        require($CFG->admdir . 'catalog_optional_params.php');

        // update info if no errors
        if (empty($errmsg)) {
            require($CFG->admdir . 'catalog_update_info.php');
        }
    }

    if (!empty($errmsg)) {
        show_error($errmsg, false);
    }

    include($CFG->admdir . 'catalog_display_input.php');

    do_submit_cancel('Guardar', '', '', 'submit-'.$catalog);
}
?>

</form>

<div class="block"></div>

<?php
do_submit_cancel('', 'Volver al Menu', get_url('admin'));
?>
