<?php
    // halt if running directly
    if (empty($CFG)) {
        die;
    }

    // check submit value
    if (empty($event->id_fecha)
        || empty($id_lugar)
        || empty($hora)) {

            $errmsg[] = "Verifica que los datos obligatorios los hayas introducido correctamente.";
    }

    if (empty($errmsg)) {
        $hora_ini = $event->hora;
        $hora_fin = $event->hora + $proposal->duracion;

        if ($hora_fin > $CFG->def_hora_fin) {
            $errmsg[] = 'La duración de esta ponencia supera la hora final del evento.';
        }

        //search for ocurrence
        for ($hhora=$hora_ini; $hhora < $hora_fin; $hhora++) {

            $testevent_place = get_record('evento_ocupa', 'id_fecha', $event->id_fecha, 'id_lugar', $event->id_lugar, 'hora', $hhora);

            if (!empty($testevent_place)) {
                if (Action == 'newevent' || $event->id != $testevent_place->id_evento) {
                    $query = 'SELECT P.id, P.nombre FROM propuesta P
                        JOIN evento E ON E.id='.$testevent_place->id_evento.'
                        WHERE P.id=E.id_propuesta GROUP BY E.id';

                    $conflict_proposal = get_record_sql($query);

                    $url = get_url('admin/proposals/'.$conflict_proposal->id);
                    $event_link = "<a href=\"{$url}\" title=\"Evento en Conflicto\">{$conflict_proposal->nombre}</a>";

                    $errmsg[] = 'La fecha, hora y lugar que elegiste tiene conflictos con otro evento ya programado: ' . $event_link;
                    break;
                }
            }
        }
    }

?>
