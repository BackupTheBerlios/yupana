<?php
    if (empty($CFG) || (Context != 'admin'
        && Context != 'ponente' && Context !='asistente')) {
        die; //exit
    }

    switch (Context) {
    case 'admin':
        $dbtable = 'administrador';
        break;
    case 'ponente':
        $dbtable = 'ponente';
        break;
    case 'asistente':
        $dbtable = 'asistente';
        break;
    }

    $is_new = (empty($USER->id)) ? true : false;

    // build user data
    $user = new StdClass;

    // existing user?
    if (!$is_new) {
        $user->id = $USER->id;
    } 

    // common values
    $user->login = $login;
    $user->nombrep = $nombrep;
    $user->apellidos = $apellidos;
    $user->mail = $mail;

    // new user or updating password?
    if (!empty($passwd)) {
        $user->passwd = md5($passwd);
    }

    if (Context == 'admin') {
        if ($is_new) {
            $user->id_tadmin = $tadmin;
        }
    }

    // shared values of asistentes and ponentes
    if (Context == 'ponente' || Context == 'asistente') {
        $user->sexo = $sexo;
        $user->ciudad = $ciudad;
        $user->org = $org;
        $user->fecha_nac = sprintf('%04d-%02d-%02d', $USER->b_year, $USER->b_month, $USER->b_day);
        $user->id_estudios = $id_estudios;
        $user->id_estado = $id_estado;

        if ($is_new) {
            $user->reg_time = strftime('%Y%m%d%H%M%S');
        }
    }

    // ponente only values
    if (Context == 'ponente') {
        $user->titulo = $titulo;
        $user->domicilio = $domicilio;
        $user->telefono = $telefono;
        $user->resume = $resume;
    }

    // asistente only values
    if (Context == 'asistente') {
        $user->id_tasistente = $id_tasistente;
    }

    if ($is_new) {
        // insert new record
        $rs = insert_record($dbtable, $user);
    } else {
        // update record
        $rs = update_record($dbtable, $user);
    }

    if (!$rs) {
        // Fatal error
        show_error('Error Fatal: No se puedo insertar/actualizar los datos.');
        die;
    }

    if ($is_new) {
?>

<p>Gracias por darte de alta, ahora ya podrás acceder a tu cuenta.</p>

<?php include($CFG->comdir . 'new_user_send_mail.php'); ?>

<?php } else { ?>
        
<p>Información actualizada.</p>

<?php } ?>

<p>Si tienes preguntas o la página no funciona correctamente, por favor
contacta a <a href="mailto:<?=$CFG->adminmail ?>">Administración <?=$CFG->conference_name ?></a></p>

<?php
    include($CFG->comdir . 'user_display_info.php');
?>
