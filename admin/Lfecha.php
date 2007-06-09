<?php 
    require_once('header-common.php');

    $rootlevel = $_SESSION['YACOMASVARS']['rootlevel'];
?>

<h1>Listado de fechas para eventos</h1>

<?php
$dates = get_records_sql('SELECT * FROM fecha_evento ORDER BY fecha');

if (!empty($dates)) {
    $table_data = array();

    $table_header = array('Fecha', 'Descripción', 'Eventos');
    if ($rootlevel == 1) {
        $table_header = array_merge($table_header, array('Acción'));
    }

    $table_data[] = $table_header;

    foreach ($dates as $date) {
        $l_fecha = <<< END
<a class="verde" href="Mfecha.php?idfecha={$date->id}">{$date->fecha}</a>
END;
        $l_eventos = <<< END
<a class="azul" href="Leventos-fecha.php?idfecha={$date->id}">Eventos registrados</a>
END;
        $row_data = array($l_fecha, $date->descr, $l_eventos);

        if ($rootlevel == 1) {
            $l_action = <<< END
<a class="precaucion" href="Bfecha.php?idfecha={$date->id}">Eliminar</a>
END;
            $row_data = array_merge($row_data, array($l_action));
        }

        $table_data[] = $row_data;
    }

    do_table($table_data, 'wide');
} else {
?>

<p class="error center">No se encontro ningún registro.</p>

<?php } ?>

<p id="buttons">
    <input type="button" value="Volver al menu" onClick="location.href='<?=$CFG->wwwroot ?>/admin/menuadmin.php#lugares'" />
</p>

<?php
do_footer();
?>
