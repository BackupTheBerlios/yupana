<?php
// dummy check
if (empty($CFG) || Context != 'admin') {
    die;
}

$rooms = get_records('lugar');
?>

<h1>Listado de lugar para eventos</h1>

<?php
if (!empty($rooms)) {
?>

<h4>Lugares registrados: <?=sizeof($rooms) ?></h4>

<?php
    // build data table
    $table_data = array();
    $table_data[] = array('Nombre', 'Ubicación', 'Disp.', '', '');

    foreach ($rooms as $room) {

        $url = get_url('admin/rooms/'.$room->id);
        $l_nombre = <<< END
<ul><li>
<a class="speaker" href="{$url}">{$room->nombre_lug}</a>
</li></ul>
END;

        $capacidad = (empty($room->cupo)) ? '--' : $room->cupo;

        $url = get_url('admin/rooms/'.$room->id.'/events');
        $l_event = "<a class=\"verde\" href=\"{$url}\">Eventos registrados</a>";

        $url = get_url('admin/rooms/'.$room->id.'/delete');
        $l_delete = "<a class=\"precaucion\" href=\"{$url}\">Eliminar</a>";
        
        $table_data[] = array(
            $l_nombre,
            $room->ubicacion,
            $capacidad,
            $l_event,
            $l_delete
            );
    }

    do_table($table_data, 'wide');

} else {
?>

<div class="block"></div>

<p class="error center">No se encontraron lugares registrados.</p>

<?php 
}
?>
