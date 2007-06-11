<?php
    // running from system?
    if (empty($CFG)) {
        die;
    }

    if ($CFG->send_mail) {
        // Build url
        if (substr($CFG->wwwroot,0,4) == 'http') {
            $url = $CFG->wwwroot . '/asistente/';
        } else {
            $url = 'http://' . $_SERVER['SERVER_NAME'] . $CFG->wwwroot . '/asistente/';
        }

        $toname = $user->nombrep .' '. $user->apellidos;
        $to = $user->mail;
        $subject = $CFG->conference_name . ': Registro de asistente';
        $message = <<< END
Te has registrado como asistente al {$CFG->conference_name}

Usuario:    {$user->login}
Contraseña: {$passwd}

Puedes iniciar sesión entrando a la siguiente dirección:

{$url}


--
Equipo {$CFG->conference_name}
{$url}

END;

        //3.. 2.. 1.. go!
        send_mail($toname, $to, $subject, $message);
?>

<p>Los datos de tu usuario y password han sido enviados al correo que registraste.</p>
<p>Es posible que algunos servidores de correo registren el correo como correo no deseado  o spam y no se encuentre en su carpeta INBOX.</p>

<?php
    } else {
?>

<p class="center">Por razones de seguridad deshabilitamos el envío de correo.</p>

<?php } ?>


