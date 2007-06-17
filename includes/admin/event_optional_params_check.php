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

        //search for ocurrence
        for ($hhora=$hora_ini; $hhora < $hora_fin; $hhora++) {

            $testevent_place = get_record('evento_ocupa', 'id_fecha', $event->id_fecha, 'id_lugar', $event->id_lugar, 'hora', $hhora);

            if (!empty($testevent_place)) {
                if (Action == 'newevent' || $event->id != $testevent_place->id_evento) {
                    $errmsg[] = 'La fecha, hora y lugar que elegiste tiene conflictos con otro evento ya programado.';
                    break;
                }
            }
        }
    }

?>
