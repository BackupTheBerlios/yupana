<?php
    // running directly?
    if (empty($CFG)) {
        die;
    }

    // shared values
    $submit = optional_param('submit');
    $login = strtolower(optional_param('S_login'));
    $passwd = optional_param('S_passwd');
    $passwd2 = optional_param('S_passwd2');
    $nombrep = optional_param('S_nombrep');
    $apellidos = optional_param('S_apellidos');
    $mail = optional_param('S_mail');
    $sexo = optional_param('C_sexo');
    $org = optional_param('S_org');
    $id_estudios = optional_param('I_id_estudios', 0, PARAM_INT);
    $ciudad = optional_param('S_ciudad');
    $id_estado = optional_param('I_id_estado', 0, PARAM_INT);
    $b_day = optional_param('I_b_day', 0, PARAM_INT);
    $b_month = optional_param('I_b_month', 0, PARAM_INT);
    $b_year = optional_param('I_b_year', 0, PARAM_INT);

    // ponente values
    $titulo = optional_param('S_titulo');
    $domicilio = optional_param('S_domicilio');
    $telefono = optional_param('S_telefono');
    $resume = optional_param('S_resume');
    
    // asistente values
    $id_tasistente = optional_param('I_id_tasistente', 0, PARAM_INT);

    // date of birth
    $fecha_nac = sprintf('%d-%02d-%02d', $b_year, $b_month, $b_day);

    // check value of sex
    $sexo = ($sexo == 'M' || $sexo == 'F') ? $sexo : '';
?>
