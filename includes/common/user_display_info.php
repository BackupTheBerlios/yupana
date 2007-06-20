<?php
    // running from system?
    if (empty($CFG)) {
        die;
    }

    // extra values
    if (Context == 'ponente'
        || Context == 'asistente'
        || Action == 'viewperson'
        || Action == 'viewspeaker'
        || Action == 'newspeaker'
        || Action == 'newperson'
        || Action == 'editspeaker'
        || Action == 'editperson'
        || Action == 'deletespeaker'
        || Action == 'deleteperson') {

        $estudios = get_field('estudios', 'descr', 'id', $user->id_estudios);
        $estado = get_field('estado', 'descr', 'id', $user->id_estado);
        $sexo = '';
        if ($user->sexo == 'F') {
            $sexo = 'Femenino';
        }
        if ($user->sexo == 'M') {
            $sexo = 'Masculino';
        }
    }

    if (Context == 'asistente'
        || Action == 'viewperson'
        || Action == 'newperson'
        || Action == 'deleteperson'
        || Action == 'editperson') {

        $tasistente = get_field('tasistente', 'descr', 'id', $user->id_tasistente);
    }

    // user values
    if (Context == 'admin'
        && (Action == 'editdetails' || Action == 'newadmin' || Action == 'deleteadmin' || Action == 'editadmin' || Action == 'viewadmin')) {

        $tadmin = get_field('tadmin', 'descr', 'id', $user->id_tadmin);

        $values = array(
            'Usuario Administrador' => $user->login,
            'Nombre(s)' => $user->nombrep,
            'Apellidos' => $user->apellidos,
            'Correo electrónico' => $user->mail,
            'Tipo administrador' => $tadmin
            );
    }

    if (Context == 'ponente'
        || Action == 'viewspeaker'
        || Action == 'newspeaker'
        || Action == 'deletespeaker'
        || Action == 'editspeaker') {

        $values = array(
            'Nombre de Usuario' => $user->login,
            'Nombre(s)' => $user->nombrep,
            'Apellidos' => $user->apellidos,
            'Correo electrónico' => $user->mail,
            'Sexo' => $sexo,
            'Organización' => $user->org,
            'Estudios' => $estudios,
            'Título' => $user->titulo,
            'Domicilio' => $user->domicilio,
            'Telefono' => chunk_split($user->telefono, 2),
            'Ciudad' => $user->ciudad,
            'Departamento' => $estado,
            'Fecha de Nacimiento' => sprintf('%s', $user->fecha_nac),
            'Resumen Curricular' => nl2br(htmlspecialchars($user->resume))
        );
    }

    if (Context == 'asistente'
        || Action == 'viewperson'
        || Action == 'newperson'
        || Action == 'editperson'
        || Action == 'deleteperson') { // should be asistente
        if (defined('SubContext') && SubContext == 'kardex') {
            $values = array(
                'Nombre de Usuario' => $user->login,
                'Correo Electrónico' => $user->mail,
                'Sexo' => $sexo,
                'Organización' => $user->org,
                'Estudios' => $estudios,
                'Tipo Asistente' => $tasistente,
                'Ciudad' => $user->ciudad,
                'Estado' => $estado
                );
        } else {
            $values = array(
                'Nombre de Usuario' => $user->login,
                'Nombre(s)' => $user->nombrep,
                'Apellidos' => $user->apellidos,
                'Correo electrónico' => $user->mail,
                'Sexo' => $sexo,
                'Organización' => $user->org,
                'Estudios' => $estudios,
                'Tipo de Asistente' => $tasistente,
                'Ciudad' => $user->ciudad,
                'Departamento' => $estado,
                'Fecha de Nacimiento' => sprintf('%s', $user->fecha_nac)
                );
        }
    }
   
    // show table with values
    do_table_values($values, 'narrow');
?>
