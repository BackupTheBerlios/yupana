<?php
    if (empty($CFG) || Context != 'admin') {
        die; //exit
    }

    // new event schedule
    if (Action == 'scheduleevent') {
        $rs = insert_record('evento', $event);

        if (!$rs) {
            // Fatal error
            show_error('No se pudo insertar los datos.');
            do_submit_cancel('', 'Regresar', get_url('admin/events'));
            die; //exit
        }

        //refresh event id
        $event->id = (int)$rs;

        if (empty($event->id)) {
            show_error('No se pudo insertar los datos. Por favor contacte su administrador.');
            die;
        }

        // continue the insert
        $hora_fin = $event->hora + $proposal->duracion;

        for ($hhora=$event->hora; $hhora < $hora_fin; $hhora++) {
            $query = 'INSERT INTO evento_ocupa
                (id_evento,hora,id_fecha,id_lugar)
                 VALUES ('.$event->id.',
                     '.$hhora.',
                     '.$event->id_fecha.',
                     '.$event->id_lugar.')';
            
            $rs = execute_sql($query, false);

            if (!$rs) {
                //FIXME: ignore errors
            }
        }

        // update proposal status
        $prop = new StdClass;
        $prop->id = $proposal->id;
        $prop->id_status = 8;

        $rs = update_record('propuesta', $prop);

    } else {
        // update records
        $rs = update_record('evento', $event);

        if (!$rs) {
            // Fatal error
            show_error('No se pudo actualizar los datos.');
            do_submit_cancel('', 'Regresar', get_url('admin/events'));
            die; //exit
        }

        // delete current references
        delete_records('evento_ocupa', 'id_evento', $event->id);

        // insert new slots
        $hora_fin = $event->hora + $proposal->duracion;

        for ($hhora=$event->hora; $hhora < $hora_fin; $hhora++) {
            $query = 'INSERT INTO evento_ocupa
                (id_evento,hora,id_fecha,id_lugar)
                 VALUES ('.$event->id.',
                     '.$hhora.',
                     '.$event->id_fecha.',
                     '.$event->id_lugar.')';
            
            $rs = execute_sql($query, false);

            if (!$rs) {
                //FIXME: ignore errors
            }
        }

        // no need to update proposal
    }

?>
