<?php
    // running from system?
    if (empty($CFG)) {
        die;
    }

    // Show values
    $values = array(
        'Nombre de Usuario' => $user->login,
        'Nombre(s)' => $user->nombrep,
        'Apellidos' => $user->apellidos,
        'Correo electrónico' => $user->mail,
        'Sexo' => ($user->sexo == 'M') ? 'Masculino' : 'Femenino',
        'Organización' => $user->org,
        'Estudios' => get_field('estudios', 'descr', 'id', $user->id_estudios),
        'Tipo de Asistente' => get_field('tasistente', 'descr', 'id', $user->id_tasistente),
        'Ciudad' => $user->ciudad,
        'Departamento' => get_field('estado', 'descr', 'id', $user->id_estado),
        'Fecha de Nacimiento' => sprintf('%s', $user->fecha_nac)
    );

    // show table with values
    do_table_values($values);
?>
