<?php
    // running from system?
    if (empty($CFG) || empty($room)) {
        die;
    }
   
    // initalize var
    $values = array();

    $cupo = (empty($room->cupo)) ? 'Salón para conferencias' : $room->cupo . ' personas';

    $values = array(
        'Nombre: ' => $room->nombre_lug,
        'Ubicación: ' => $room->ubicacion,
        'Capacidad' => $cupo
        );

    do_table_values($values, 'narrow');
?>
