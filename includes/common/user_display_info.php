<?php
    // running from system?
    if (empty($CFG)) {
        die;
    }

    // extra values
    if (Context == 'ponente'
        || Context == 'asistente') {
        $estudios = get_field('estudios', 'descr', 'id', $USER->id_estudios);
        $estado = get_field('estado', 'descr', 'id', $USER->id_estado);
        $sexo = '';
        if ($USER->sexo == 'F') {
            $sexo = 'Femenino';
        }
        if ($USER->sexo == 'M') {
            $sexo = 'Masculino';
        }
    }

    if (Context == 'asistente') {
        $tasistente = get_field('tasistente', 'descr', 'id', $USER->id_tasistente);
    }

    // user values
    if (Context == 'admin') {
        $values = array(
            'Usuario Administrador' => $USER->login,
            'Nombre(s)' => $USER->nombrep,
            'Apellidos' => $USER->apellidos,
            'Correo electrónico' => $USER->mail
            );
    }

    if (Context == 'ponente') {
        $values = array(
            'Nombre de Usuario' => $USER->login,
            'Nombre(s)' => $USER->nombrep,
            'Apellidos' => $USER->apellidos,
            'Correo electrónico' => $USER->mail,
            'Sexo' => $sexo,
            'Organización' => $USER->org,
            'Estudios' => $estudios,
            'Título' => $USER->titulo,
            'Domicilio' => $USER->domicilio,
            'Telefono' => chunk_split($USER->telefono, 2),
            'Ciudad' => $USER->ciudad,
            'Departamento' => $estado,
            'Fecha de Nacimiento' => sprintf('%s', $USER->fecha_nac),
            'Resumen Curricular' => $USER->resume
        );
    }

    if (Context == 'asistente') { // should be asistente
        if (defined('SubContext') && SubContext == 'kardex') {
            $values = array(
                'Nombre de Usuario' => $USER->login,
                'Correo Electrónico' => $USER->mail,
                'Sexo' => $sexo,
                'Organización' => $USER->org,
                'Estudios' => $estudios,
                'Tipo Asistente' => $tasistente,
                'Ciudad' => $USER->ciudad,
                'Estado' => $estado
                );
        } else {
            $values = array(
                'Nombre de Usuario' => $USER->login,
                'Nombre(s)' => $USER->nombrep,
                'Apellidos' => $USER->apellidos,
                'Correo electrónico' => $USER->mail,
                'Sexo' => $sexo,
                'Organización' => $USER->org,
                'Estudios' => $estudios,
                'Tipo de Asistente' => $tasistente,
                'Ciudad' => $USER->ciudad,
                'Departamento' => $estado,
                'Fecha de Nacimiento' => sprintf('%s', $USER->fecha_nac)
                );
        }
    }
   
    // show table with values
    do_table_values($values);
?>
