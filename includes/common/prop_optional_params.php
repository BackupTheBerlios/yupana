<?php
    // running directly?
    if (empty($CFG)) {
        die;
    }

    // Common values
    $submit = optional_param('submit');
    $idponencia = optional_param('idponencia');
    $nombreponencia = optional_param('S_nombreponencia');
    $id_nivel = optional_param('I_id_nivel', 0, PARAM_INT);
    $id_tipo = optional_param('I_id_tipo', 0, PARAM_INT);
    $id_orientacion = optional_param('I_id_orientacion', 0, PARAM_INT);
    $duracion = optional_param('I_duracion', 0, PARAM_INT);
    $resumen = trim(optional_param('S_resumen'));
    $reqtecnicos = trim(optional_param('S_reqtecnicos'));
    $reqasistente = trim(optional_param('S_reqasistente'));

    // input file name
    if (!empty($_FILES['fichero']['name'])) {

    }

    // attributes
    $attrs = array(
        'nombreponencia',
        'id_nivel',
        'id_tipo',
        'id_orientacion',
        'duracion',
        'resumen',
        'reqtecnicos',
        'reqasistente'
    );

    if (Context == 'admin') {
        $login = optional_param('login');
        $attrs[] = 'login';
    }

    // fill proposal info
    if (!empty($submit) || Action == 'newproposal') {
        // initialize $proposal if not
//        if (empty($proposal) || !is_object($proposal)) {
//            $proposal = new StdClass;
//        }

        foreach ($attrs as $attr) {
            $proposal->$attr = $$attr;
        }
    }

?>
