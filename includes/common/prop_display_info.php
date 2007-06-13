<?php
    // running from system?
    if (empty($CFG) || empty($proposal)) {
        die;
    }
   
    // flag prop_noshow_resume

    $values = array(
        'Nombre de Ponencia' => $proposal->ponencia,
        'Nivel' => $proposal->nivel,
        'Tipo de Propuesta' => $proposal->tipo,
        'Orientación' => $proposal->orientacion,
        'Duración' => sprintf('%d Hrs.', $proposal->duracion),
//        'Status' => $proposal->status
        );

    if (!defined('Action') || (Action != 'newproposal' && Action != 'updateproposal')) {
        $values['Status'] = '<b>' . $proposal->status . '</b>';
    }

    if (Context == 'ponente' && !empty($proposal->id_administrador)) {
        $contactmail = get_field('administrador', 'mail', 'id', $proposal->id_administrador);
        $contactmail = sprintf("<em>%s</em>", $contactmail);

        $values = array_merge($values,
                 array('Correo de contacto' => $contactmail));
    }

    // if it's schedule merge date info
    if ($proposal->id_status == 8) {
        $query = '
            SELECT FE.fecha, L.nombre_lug AS lugar, EO.hora
            FROM evento E
            JOIN evento_ocupa EO ON EO.id_event = E.id
            JOIN fecha_evento FE ON FE.id = E.id_fecha
            JOIN lugar L ON L.id = E.id_lugar
            WHERE E.id_propuesta = '.$proposal_id;

        $schedule = get_record_sql($query);
        $endhour = $schedule->hora + $proposal->duracion -1;
        $schedule->time = sprintf('%02d:00 - %02d:50', $schedule->hora, $endhour);

        $values = array_merge($values, array(
                'Fecha' => $schedule->fecha,
                'Lugar' => $schedule->lugar,
                'Hora' => $schedule->time
            ));
    }

    // show proposal info
    do_table_values($values, 'narrow');

    if (empty($prop_noshow_resume)) {
        // reset values
        $values = array();

        // show proposal aditional info
        $values['Resumen'] = $proposal->resumen;

        if (Context == 'ponente') {
            $values['Requisitos técnicos del taller'] = $proposal->reqtecnicos;
        }

        if (Context == 'ponente' || !empty($proposal->reqasistente)) {
            $values['Prerequisitos del Asistente'] = $proposal->reqasistente;
        }

        if (defined('Action') && (Action == 'newproposal' || Action == 'updateproposal')) {
            //TODO: show file name
        }

        // show proposal aditional info
        do_table_values($values, 'narrow');
    }
?>
