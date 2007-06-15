<?php
    // running from system?
    if (empty($CFG) || empty($proposal)) {
        die;
    }
   
    // initalize var
    $values = array();

    if (Action == 'updateproposal' || Action == 'deleteproposal') {
        $values['Nombre de ponencia'] = $proposal->nombre;
    }

    // flag prop_noshow_resume
    if (empty($prop_noshow_resume)) {
        $values['Resumen'] = nl2br(htmlspecialchars($proposal->resumen));
    }

    $values = array_merge($values, array(
        'Tipo de Propuesta' => $proposal->tipo,
        'Orientación' => $proposal->orientacion,
        'Duración' => sprintf('%d Hrs.', $proposal->duracion),
        'Nivel' => $proposal->nivel,
        ));

    if (!defined('Action') || (Action != 'newproposal' && Action != 'updateproposal')) {
        $values['Status'] = '<b>' . $proposal->status . '</b>';
    }

    if (Context == 'ponente' && !empty($proposal->id_administrador)) {
        $contactmail = sprintf('<em>%s</em>', $proposal->adminmail);

        $values = array_merge($values, array(
                'Correo de contacto' => $contactmail
            ));
    }

    do_table_values($values, 'narrow');

    // if it's schedule merge date info
    if ($proposal->id_status == 8) {
        $values = array(
                'Fecha' => $proposal->human_date,
                'Lugar' => $proposal->lugar,
                'Hora' => $proposal->time
            );

        do_table_values($values, 'narrow');
    }

    if (empty($prop_noshow_resume)) {
        // reset values
        $values = array();

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
