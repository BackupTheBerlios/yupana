<?php
// dummy check
if (empty($q) || empty($CFG) || $USER->id_tadmin != 1) {
    die;
}

if (Action == 'deleteperson') {
    preg_match('#^admin/persons/(\d+)/delete$#', $q, $matches);
    $user_id = (!empty($matches)) ? (int) $matches[1] : 0;
    $desc = 'Asistente';
    $dbtable = 'asistente';
    $user = get_person($user_id);
    $local_url = 'persons';
} 

elseif (Action == 'deletespeaker') {
    preg_match('#^admin/speakers/(\d+)/delete$#', $q, $matches);
    $user_id = (!empty($matches)) ? (int) $matches[1] : 0;
    $desc = 'Ponente';
    $dbtable = 'ponente';
    // protect event admin speaker
    if ($user_id == 1) {
        $optional_message = 'No puedes eliminar este usuario, esta reservado como administrador de eventos.';
    } else {
        $user = get_speaker($user_id);
    }
    $local_url = 'speakers';
}

elseif (Action == 'deleteadmin') {
    preg_match('#^admin/(\d+)/delete$#', $q, $matches);
    $user_id = (!empty($matches)) ? (int) $matches[1] : 0;
    $desc = 'Administrador';
    $dbtable = 'administrador';
    if ($user_id == 1) {
        $optional_message = 'No puedes eliminar al administrador principal.';
    } else {
        $user = get_admin($user_id);
    }
    $local_url = 'list';
}

$submit = optional_param('submit');
?>

<h1>Eliminar <?=$desc ?></h1>

<?php
// check if user want to delete himself or main admin
if (!empty($user) && ($user_id != $USER->id || Action == 'deleteperson'))  {

    if (empty($submit)) {
        // confirm delete
?>

<form method="POST" action="";

<?php
        include($CFG->comdir . 'user_display_info.php');
        do_submit_cancel('Eliminar', 'Cancelar', get_url('admin/'.$local_url));
?>

</form>

<?php
    } else {
        // delete!
        // (really change status to deleted)

        // this never should be happen
        if (Action == 'deleteadmin' && $user_id == 1) {
            die;
        }

        $fail = null;

        if (Action == 'deleteadmin') {
            $return_url = get_url('admin/list');

            // first try update reference
            $prop_update = 'UPDATE propuesta SET id_administrador=1 WHERE id_administrador='.$user->id;
            $event_update = 'UPDATE evento SET id_administrador=1 WHERE id_administrador='.$user->id;

            if (!execute_sql($prop_update, false) || !execute_sql($event_update, false)) {
                show_error('Ocurrio un error al intentar eliminar el registro.');
                $rs = null;
            } else {
                $rs = delete_records('administrador', 'id', $user->id);
            }

            $desc_more = 'Las propuestas que ha aprobado han sido reasiganadas al administrador principal.';
        }

        elseif (Action == 'deletespeaker') {
            $return_url = get_url('admin/speakers');

            // get user proposals
            $props = get_records('propuesta', 'id_ponente', $user->id);

            if (!empty($props)) {
                // delete events references
                foreach ($props as $prop) {
                    $event_id = get_field('evento', 'id', 'id_propuesta', $prop->id);
                    // delete event_place
                    delete_records('evento_ocupa', 'id_evento', $event_id);
                    // delete subscriptions
                    delete_records('inscribe', 'id_evento', $event_id);
                    // finally delete event
                    delete_records('evento', 'id', $event_id);
                    // delete prop
                    delete_records('propuesta', 'id_ponente', $user->id);
                }
            }

            // and... delete user
            $rs = delete_records('ponente', 'id', $user->id);

            $desc_more = 'Las ponencias que el usuario ha enviado han sido eliminadas, los eventos relacionados y los inscritos a sus talleres.';
        }

        elseif (Action == 'deleteperson') {
            $return_url = get_url('admin/persons');

            // delete user
            $rs = delete_records('asistente', 'id', $user->id);

            // delete user subscriptions
            delete_records('inscribe', 'id_asistente', $user->id);

            $desc_more = 'Los espacios que ocupaba en los talleres han sido liberados.';
        }


        if (!$rs) {
            show_error('Ocurrio un error al eleminar el registro.');
        } else {
?> 

<div class="block"></div>

<p class="center"><?=$desc ?>fue eliminado exitosamente.</p>
<p class="center"><?=$desc_more ?></p>

<?php 
        }

        do_submit_cancel('', 'Continuar', $return_url);
    }

} else {
?>

<h1><?=$desc ?> no encontrado</h1>

<div class="block"></div>
<p class="center">El usuario no existe.</p>
<p class="center"><?=$optional_message ?></p>

<?php
    do_submit_cancel('', 'Regresar', $return_url);
}
?>
