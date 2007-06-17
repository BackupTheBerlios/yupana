<?php
    // running directly?
    if (empty($CFG) || Context != 'admin') {
        die;
    }

    // Common values
    $submit = optional_param('submit');

    $id_fecha = optional_param('I_id_fecha', 0, PARAM_INT);
    $id_lugar = optional_param('I_id_lugar', 0, PARAM_INT);
    $hora = optional_param('I_hora', 0, PARAM_INT);

    if (!empty($submit) || Action == 'scheduleevent') {
        $event->id_fecha = $id_fecha;
        $event->id_lugar = $id_lugar;
        $event->hora = $hora;
    }
?>
