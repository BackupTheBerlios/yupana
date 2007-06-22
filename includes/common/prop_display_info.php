<?php
    // running from system?
    if (empty($CFG) || empty($proposal)) {
        die;
    }
   
    // initalize var
    $values = array();

    if (Context == 'admin' || Action == 'newproposal' || Action == 'updateproposal' || Action == 'deleteproposal' || Action == 'scheduleevent' || Action == 'editevent' || Action == 'cancelevent' || Action == 'proposalfiles') {
        if (Context == 'admin' && Action != 'scheduleevent' && Action != 'editevent') {
            $values['Nombre de Usuario'] = $proposal->login;
        }

        $values['Nombre de ponencia'] = $proposal->nombre;

        if (Action == 'scheduleevent' || Action == 'editevent') {
            $values['Nombre de ponente'] = $proposal->nombrep . ' ' . $proposal->apellidos;
        }
    }

    // flag prop_noshow_resume
    if (empty($prop_noshow_resume)) {
        $values['Resumen'] = nl2br(htmlspecialchars($proposal->resumen));
    }

    if (Action != 'proposalfiles') {
        $values = array_merge($values, array(
            'Tipo de Propuesta' => $proposal->tipo,
            'Orientación' => $proposal->orientacion,
            'Duración' => sprintf('%d Hrs.', $proposal->duracion),
            'Nivel' => $proposal->nivel,
            ));
    }

    if (Action != 'newproposal' && Action != 'updateproposal' && Action != 'scheduleevent' && Action != 'editevent') {
        $values['Status'] = '<b>' . $proposal->status . '</b>';
    }

    if (Context == 'ponente' && !empty($proposal->adminmail) && Action != 'proposalfiles') {
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

        if (!empty($proposal->reqtecnicos) && (Context == 'ponente' || Context == 'admin')) {
            $values['Requisitos técnicos del taller'] = $proposal->reqtecnicos;
        }

        if (!empty($proposal->reqasistente) && (Context == 'ponente' || Context == 'admin')) {
            $values['Prerequisitos del Asistente'] = $proposal->reqasistente;
        }

        if (!empty($values)) {
            // show proposal aditional info
            do_table_values($values, 'narrow');
        }
    }

    // show public files of proposals if it's programmed
    if (Action == 'viewproposal' && ($proposal->id_status == 8 || $proposal->id_ponente == $USER->id || Context == 'admin')) {
        //show files
        $files = get_records('prop_files', 'id_propuesta', $proposal->id);

        $filelist = '';

        if (!empty($files)) {

            foreach ($files as $f) {
                if (!empty($f->public) || (!empty($USER) && $proposal->id_ponente == $USER->id) || Context == 'admin') {

                    $size = human_filesize($f->size);
                    $title = htmlspecialchars($f->title, ENT_COMPAT, 'utf-8');

                    if (Context == 'main') {
                        $url = get_url('general/proposals/'.$proposal->id.'/files/'.$f->id.'/'.$f->name);
                    }

                    elseif (Context == 'asistente') {
                        $url = get_url('person/proposals/'.$proposal->id.'/files/'.$f->id.'/'.$f->name);
                    }

                    elseif (Context == 'ponente') {
                        $url = get_url('speaker/proposals/'.$proposal->id.'/files/'.$f->id.'/'.$f->name);
                    }

                    elseif (Context == 'admin') {
                        $url = get_url('admin/proposals/'.$proposal->id.'/files/'.$f->id.'/'.$f->name);
                    }

                    if (empty($f->public)) {
                        $private = '*';
                    } else {
                        $private = '';
                    }

                    $filelist .= <<< END
    <li><a href="{$url}" title="({$f->name}) {$f->descr}">{$title}</a>{$private} <small>({$size})</small></li>
END;

                }
            }

        }

        if (!empty($USER) && Context == 'speaker' && $proposal->id_ponente == $USER->id) {
            $url = get_url('speaker/proposals/'.$proposal->id.'/files');
            $filelist .= "<li><a class=\"verde\" href=\"{$url}\">Subir archivos</a></li>";
        }

        if (!empty($filelist)) {
            $filelist = "<ul>{$filelist}</ul>";
            do_table_values(array('Archivos' => $filelist), 'narrow');
        }

    }

    if (Context == 'admin' && Action != 'newproposal' && Action != 'scheduleevent' && Action != 'editevent') {
        $adminlogin = (empty($proposal->adminlogin)) ? 'Usuario' : $proposal->adminlogin;

        $values = array(
            'Fecha de registro' => $proposal->reg_time,
            'Fecha de actualización' => $proposal->act_time,
            'Actualizado por' => $adminlogin
            );

        do_table_values($values, 'narrow');
    }

?>
