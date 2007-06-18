<?php
if (empty($CFG) || (Action != 'addschedule' && Action != 'addschedule_action')) {

    header('Location: ' . get_url());
}

$errmsg = array();
?>

<h1>Programar Evento</h1>

<?php
if (Action == 'addschedule') {
    preg_match('#^admin/schedule/add/(\d+)/(\d+)/(\d+)$#', $q, $matches);
} 

elseif (Action == 'addschedule_action') {
    preg_match('#^admin/schedule/add/(\d+)/(\d+)/(\d+)/(\d+)$#', $q, $matches);
}

$room_id = (empty($matches[1])) ? 0 : (int) $matches[1];
$date_id = (empty($matches[2])) ? 0 : (int) $matches[2];
$hour = (empty($matches[3])) ? 0 : (int) $matches[3];

if (Action == 'addschedule_action') {
    $proposal_id = (empty($matches[4])) ? 0 : (int) $matches[4];
    $proposal = get_record('propuesta', 'id', $proposal_id);

    if (empty($proposal)) {
        $errmsg[] = 'La ponencia que seleccionaste no existe.';
    }

    elseif ($proposal->id_status != 5) {
        $errmsg[] = 'No se puede asignar esta ponencia.';
    }
}

$room = get_record('lugar', 'id', $room_id);
$date = get_record('fecha_evento', 'id', $date_id);

if (empty($room)) {
    $errmsg[] = 'El lugar no existe.';
}

if (empty($date)) {
    $errmsg[] = 'La fecha no existe.';

}

if ($hour < $CFG->def_hora_ini || $hour >= $CFG->def_hora_fin) {
    $errmsg[] = 'La hora que elegiste queda fuera de la duración del evento.';
}

$event_slot = get_record('evento_ocupa', 'id_fecha', $date->id, 'id_lugar', $room->id, 'hora', $hour);

if (!empty($event_slot)) {
    $errmsg[] = 'Esta fecha, hora y lugar ya tiene un evento programado.';
}

if (!empty($errmsg)) {
    show_error($errmsg, false);
?>

<div class="block"></div>

<?php
}

else {
    if (Action == 'addschedule_action') {
        $event = new StdClass;
        $event->id_fecha = $date->id;
        $event->id_lugar = $room->id;
        $event->id_propuesta = $proposal->id;
        $event->hora = $hour;

        require($CFG->admdir . 'event_optional_params_check.php');

        if (!empty($errmsg)) {
            show_error($errmsg);
        } else {
            // set event    
            require($CFG->admdir . 'event_update_info.php');

            // check if was inserted
            if (!empty($event->id)) {
?>

<p class="error center">El evento ha sido añadido con éxito.</p>

<div class="block"></div>
<?php
                do_submit_cancel('', 'Continuar', get_url('admin/schedule'));
            } else {

                $errmsg[] = 'Ocurrió un error al insertar los datos.';
                show_error($errmsg);

                
            }
?>

<div class="block"></div>

<?php
        }

    }

    else {

        $values = array(
            'Lugar' => $room->nombre_lug,
            'Fecha' => friendly_date($date->fecha),
            'Hora' => sprintf('%02d:00 hrs.', $hour)
            );

        do_table_values($values, 'narrow');
?>

<h2 class="center">Ponencias disponibles</h2>

<?php

        include($CFG->comdir . 'prop_list.php');

        do_submit_cancel('', 'Regresar', get_url('admin/schedule'));
    }
}

if (!empty($errmsg)) {
    do_submit_cancel('', 'Regresar', get_url('admin/schedule'));
}
?>
