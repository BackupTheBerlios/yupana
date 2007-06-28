<?php
// dummy check
if (empty($q) || empty($CFG) || Context != 'admin') {
    die;
}

preg_match('#^admin/rooms/(\d+)/delete$#', $q, $matches);
$room_id = (!empty($matches)) ? (int) $matches[1] : 0;

$room = get_record('lugar', 'id', $room_id);

$submit = optional_param('submit');
?>

<h1>Eliminar lugar para eventos</h1>

<?php
//check owner and status, dont delete acepted, scheduled or deleted¿?
if (!empty($room)) {

    if (empty($submit)) {
        // confirm delete
?>

<form method="POST" action="";

<?php
        include($CFG->admdir . 'room_display_info.php');
        do_submit_cancel('Eliminar', 'Cancelar', $return_url);
?>

</form>

<?php
    } else {
        // delete!
        // first update references
        $events = get_records_sql('SELECT id_evento FROM '.$CFG->prefix.'evento_ocupa WHERE id_lugar='. $room->id .' GROUP BY id_evento');

        if (!empty($events)) {
            // delete events-rooms
            delete_records('evento_ocupa', 'id_lugar', $room->id);

            // update proposals
            foreach ($events as $event) {
                $proposal_id = get_field('evento', 'id_propuesta', 'id', $event->id_evento);

                if (!empty($proposal_id)) {
                    $proposal = new StdClass;
                    $proposal->id = $proposal_id;
                    //new status
                    $proposal->id_status = 5;

                    update_record('propuesta', $proposal);
                }

                //delete event
                delete_records('evento', 'id', $event->id_evento);
                //delete subscriptions
                delete_records('inscribe', 'id_evento', $event->id_evento);
            }
        }

        // finally delete room
        if (!$rs = delete_records('lugar', 'id', $room->id)) {
            show_error('Ocurrio un error al eleminar el registro.');
        } else {
?> 

<div class="block"></div>

<p class="center">El lugar para eventos fue eliminado exitosamente.
Los espacios que ocupaban los asistentes inscritos en los talleres has sido liberados. Las ponencias registradas relacionadas con el lugar han sido cambiadas de estado a "Aceptada" para su nueva asignación.</p>

<?php 
        }

        do_submit_cancel('', 'Continuar', $return_url);
    }

} else {
?>

<h1>Lugar no encontrado</h1>

<div class="block"></div>
<p class="center">Registros del lugar no encontrados. Posiblemente no existan o no tengas acceso para eliminarlo.</p>

<?php
    do_submit_cancel('', 'Regresar', $return_url);
}
?>
