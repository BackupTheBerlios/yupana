<?php
    // running from system?
    if (empty($CFG)) {
        die;
    }

    // extra values
    $sexo = ($user->sexo == 'M') ? 'Masculino' : 'Femenino';
    $estudios = get_field('estudios', 'descr', 'id', $user->id_estudios);
    $tasistente = get_field('tasistente', 'descr', 'id', $user->id_tasistente);
    $estado = get_field('estado', 'descr', 'id', $user->id_estado);

    // Show values
    if (!empty($hoja_registro)) {

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

    // show table with values
    do_table_values($values);
?>
