<?php
    require_once('header-common.php');

    $rootlevel = $_SESSION['YACOMASVARS']['rootlevel'];
?>

<h1>Listado de lugares para eventos</h1>

<?php
$rooms = get_records('lugar');

if (!empty($rooms)) {
    $table_data = array();

    $table_header = array('Nombre', 'Ubicación', 'Cupo', 'Eventos');
    if ($rootlevel == 1) {
        $table_header = array_merge($table_header, array('Acción'));
    }

    $table_data[] = $table_header;

    foreach ($rooms as $room) {
        $l_nombre = <<< END
<a class="verde" href="Mlugar.php?idlugar={$room->id}">{$room->nombre_lug}</a>
END;
        $l_cupo = ($room->cupo != 99999) ? $room->cupo : '';
        $l_eventos = <<< END
<a class="azul" href="Leventos-lugar.php?idlugar={$room->id}">Eventos Registrados</a>
END;

        $row_data = array($l_nombre, $room->ubicacion, $l_cupo, $l_eventos);

        if ($rootlevel == 1) {
            $l_action = <<< END
<a class="precaucion" href="Blugar.php?idlugar={$room->id}">Eliminar</a>
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
