<?php
    // running from system?
    if (empty($CFG)) {
        die;
    }

    $estudios = get_field('estudios', 'descr', 'id', $user->id_estudios);
    $tasistente = get_field('tasistente', 'descr', 'id', $user->id_tasistente);
    $estado = get_field('estado', 'descr', 'id', $user->id_estado);

    // Show values
    $values = array(
        'Nombre de Usuario' => $user->login,
        'Nombre(s)' => $user->nombrep,
        'Apellidos' => $user->apellidos,
        'Correo electrónico' => $user->mail,
        'Sexo' => ($user->sexo == 'M') ? 'Masculino' : 'Femenino',
        'Organización' => $user->org,
        'Estudios' => $estudios,
        'Tipo de Asistente' => $tasistente,
        'Ciudad' => $user->ciudad,
        'Departamento' => $estado,
        'Fecha de Nacimiento' => sprintf('%s', $user->fecha_nac)
    );

    // show table with values
    do_table_values($values);
?>
