<?php
    // running from system?
    if (empty($CFG) || empty($proposal)) {
        die;
    }
   
    // initalize var
    $values = array();

    if (Action == 'newproposal' || Action == 'updateproposal' || Action == 'deleteproposal' || Action == 'newevent' || Action == 'editevent') {
        if (Context == 'admin' && Action != 'newevent' && Action != 'editevent') {
            $values['Nombre de Usuario'] = $login;
        }

        $values['Nombre de ponencia'] = $proposal->nombre;

        if (Action == 'newevent' || Action == 'editevent') {
            $values['Nombre de ponente'] = $proposal->nombrep . ' ' . $proposal->apellidos;
        }
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

    if (Action != 'newproposal' && Action != 'updateproposal' && Action != 'newevent' && Action != 'editevent') {
        $values['Status'] = '<b>' . $proposal->status . '</b>';
    }

    if (Context == 'ponente' && !empty($proposal->adminmail)) {
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

    if (Context == 'admin' && Action != 'newproposal' && Action != 'newevent' && Action != 'editevent') {
        $adminlogin = (empty($proposal->adminlogin)) ? 'Usuario' : $proposal->adminlogin;

        $values = array(
            'Fecha de registro' => $proposal->reg_time,
            'Fecha de actualización' => $proposal->act_time,
            'Actualizado por' => $adminlogin
            );

        do_table_values($values, 'narrow');
    }

    if (empty($prop_noshow_resume)) {
        // reset values
        $values = array();

        if (Context == 'ponente' || Context == 'admin') {
            $values['Requisitos técnicos del taller'] = $proposal->reqtecnicos;
        }

        if ((Context == 'ponente' || !empty($proposal->reqasistente)) || Context == 'admin') {
            $values['Prerequisitos del Asistente'] = $proposal->reqasistente;
        }

        if (Action == 'newproposal' || Action == 'updateproposal') {
            //TODO: show file name
        }

        // show proposal aditional info
        do_table_values($values, 'narrow');
    }

?>
