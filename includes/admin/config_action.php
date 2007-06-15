<?php

if (empty($CFG) || empty($q) || Context != 'admin') {
    die;
}

preg_match('#^admin/config/(open|close)/(\d+)#', $q, $matches);
$action = $matches[1];
$reg_id = (int) $matches[2];

// update reg flag status
if (!empty($reg_id)) {
    $reg = new StdClass;
    $reg->id = $reg_id;

    switch ($reg->id) {
        case 1:
           $reg_desc = 'Registro de ponentes';
           break;

        case 2:
           $reg_desc = 'Registro de asistentes';
           break;

        case 3:
           $reg_desc = 'Registro de ponencias';
           break;

        case 4:
           $reg_desc = 'Inscripción a Talleres/Tutoriales';
           break;

    }

    switch ($action) {
        case 'open':
            $reg->status = 1;
            $action_desc = 'Abierto';
            break;

        case 'close':
            $reg->status = 0;
            $action_desc = 'Cerrado';
            break;
    }

    if ($rs = update_record('config', $reg)) {
        $errmsg[] = 'El Registro se ha ' . $action_desc;
    } else {
        $errmsg[] = 'Ocurrió un error al actualizar cambiar el estado del registro';
    }
}

header('Location: ' . get_url('admin/config'));
