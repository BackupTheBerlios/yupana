<?php
if (empty($CFG)) {
    die;
}

if (!empty($submit) && $proposal->id_status < 6) {
    $c = new StdClass;
    $c->body = optional_param('S_c_body');

    if (empty($c->body)) {
        show_error('El campo del comentario se encuentra vacío.');
    }

    elseif (record_exists('prop_comments', 'body', $c->body)) {
        show_error('El comentario ya ha sido enviado anteriormente.');
    } 

    else {
        $c->id_propuesta = $proposal->id;
        $c->login = $USER->login;

        if (Context == 'admin') {
            $c->author_type = 0;
        }

        elseif (Context == 'ponente') {
            $c->author_type = 1;
        }

        else {
            die; //this never should be happen
        }

        //insert comment
        if ($rs = insert_record('prop_comments', $c)) {
            show_error('Comentario registrado con éxito.', false);

            //TODO: send mail notification
            if ($CFG->send_mail && $proposal->id_ponente != $USER->id) {
                $toname = $user->nombrep . ' ' . $user->apellidos;
                $subject = $CFG->conference_name . ': Nuevo comentario';
                $url = get_url('speaker/proposals/'.$proposal->id);
                $message = <<< END
Tu propuesta "{$proposal->nombre}" ha recibido un nuevo comentario del usuario {$c->login}:

{$c->body}

{$url}
--
Equipo {$CFG->conference_name}
{$CFG->conference_url}
END;
                send_mail($toname, $to, $subject, $message);
            }

            //clear c->body
            $c = new StdClass;
            $c->body = '';
        } else {
            show_error('Ocurrió un error al registrar el comentario.');
        }
    }
} else {
    //clear c->body
    $c = new StdClass;
    $c->body = '';
}
?>

