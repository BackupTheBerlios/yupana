<?php
    // running from system?
    if (empty($CFG) || empty($date)) {
        die;
    }

    // initalize var
    $values = array(
        'Fecha de evento: ' => friendly_date($date->fecha, true),
        'Descripción: ' => $date->descr
        );

    do_table_values($values, 'narrow');
?>
