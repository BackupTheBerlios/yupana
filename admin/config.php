<?php 
// configuration manager
// just switch on/off conf flags
require_once('header-common.php');
?>

<h1>Configuración Yacomas</h1>

<table>
    <tr class="table-headers">
        <td>Nombre</td><td>Estado</td>
        <td>Acción</td>
	</tr>

<?php
    if ($conf_vals=get_records('config')) {
        $trclass = 'even';
        foreach ($conf_vals as $conf) {
?>

    <tr class="<?=($trclass=='even') ? $trclass : 'odd' ?>">

        <td><?=$conf->descr ?></td>
        <td><?=($conf->status==1) ? 'Abierto' : 'Cerrado' ?></td>

        <td><a class="<?=($conf->status==1) ? 'precaucion' : 'verde' ?>" href="act_conf.php?vconf=<?=$conf->id ?> <?=($conf->status==1) ? 0 : 1 ?>"><?=($conf->status==1) ? 'Cerrar' : 'Abrir' ?></a>
        </td>

    </tr>

<?php
            // toggle trclass
            $trclass = ($trclass=='even') ? 'odd' : 'even';
        }
    }
?>

</table>
    
<p id="buttons">
    <input type="button" value="Volver al menu" onClick="location.href='<?=$CFG->wwwroot ?>/admin/menuadmin.php'" />
</p>

<?php
do_footer();
?>
