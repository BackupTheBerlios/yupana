<?php 
// configuration manager
// just switch on/off conf flags
require_once('header-common.php');
?>

<h1>Configuración Yacomas</h1>

<?php
if ($conf_values=get_records('config')) {

    $table_data = array();
    $table_data[] = array('Nombre', 'Estado', 'Acción');

    foreach($conf_values as $conf) {
        $status_desc = ($conf->status) ? 'Abierto' : 'Cerrado';
        // toggle status
        $status_toggle = ($conf->status) ? 0 : 1;

        $action_desc = ($conf->status) ? 'Cerrar' : 'Abrir';
        $action_class = ($conf->status) ? 'precaucion' : 'verde';

        $action = <<< END
<a class="{$action_class}" href="act_conf.php?vconf={$conf->id} {$status_toggle}">{$action_desc}</a>
END;

        $table_data[] = array($conf->descr, $status_desc, $action);
    }

    do_table($table_data);
} else {
?>

<h2 class="error center">No se pudo obtener los valores de configuración. Consulte a su administrador</h2>

<?php } ?>
    
<p id="buttons">
    <input type="button" value="Volver al menu" onClick="location.href='<?=$CFG->wwwroot ?>/admin/menuadmin.php'" />
</p>

<?php
do_footer();
?>
