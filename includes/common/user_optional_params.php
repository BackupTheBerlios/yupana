<?php
    // running directly?
    if (empty($CFG)) {
        die;
    }

    if (Context == 'admin' || Context == 'ponente' || Context == 'asistente') {
        // shared values of all type of users
        $submit = optional_param('submit');

        $login = strtolower(optional_param('S_login'));
        $passwd = optional_param('S_passwd');
        $passwd2 = optional_param('S_passwd2');
        $nombrep = optional_param('S_nombrep');
        $apellidos = optional_param('S_apellidos');
        $mail = optional_param('S_mail');
    }

    if (Context == 'ponente' || Context == 'asistente') {
        // shared user values 
        $sexo = optional_param('C_sexo');
        $org = optional_param('S_org');
        $id_estudios = optional_param('I_id_estudios', 0, PARAM_INT);
        $ciudad = optional_param('S_ciudad');
        $id_estado = optional_param('I_id_estado', 0, PARAM_INT);
        $b_day = optional_param('I_b_day', 0, PARAM_INT);
        $b_month = optional_param('I_b_month', 0, PARAM_INT);
        $b_year = optional_param('I_b_year', 0, PARAM_INT);
    }

    if (Context == 'admin') {
        $id_tadmin = optional_param('I_id_tadmin', 0, PARAM_INT);
    }

    if (Context == 'ponente') {
        // ponente values
        $titulo = optional_param('S_titulo');
        $domicilio = optional_param('S_domicilio');
        $telefono = optional_param('S_telefono');
        $resume = optional_param('S_resume');
    }
    
    if (Context == 'asistente') {
        // asistente values
        $id_tasistente = optional_param('I_id_tasistente', 0, PARAM_INT);
    }

    // set $USER object if empty
    if (empty($USER) || !is_object($USER)) {
        $USER = new StdClass;
    }

    // load input data into $USER
    $attrs = array();

    if (Context == 'admin' || Context == 'ponente' || Context == 'asistente') {
        $add_attrs = array(
            'login',
            'passwd',
            'passwd2',
            'nombrep',
            'apellidos',
            'mail'
            );

        $attrs = array_merge($attrs, $add_attrs);
    }

    if (Context == 'ponente' || Context == 'asistente') {
        $add_attrs = array(
            'sexo',
            'org',
            'id_estudios',
            'ciudad',
            'id_estado'
            );

        $attrs = array_merge($attrs, $add_attrs);
    }

    if (Context == 'admin') {
        $attrs = array_merge($attrs, array('id_tadmin'));
    }

    if (Context == 'ponente') {
        $add_attrs = array(
            'titulo',
            'domicilio',
            'telefono',
            'resume'
            );
        $attrs = array_merge($attrs, $add_attrs);
    }

    if (Context == 'asistente') {
        $attrs = array_merge($attrs, array('id_tasistente'));
    }

    // fill $USER attributes
    foreach ($attrs as $attr) {
        if (!empty($submit) || defined('Register')) {
            // update values from input
            $USER->$attr = $$attr;
        }
    }

    // set birthday 
    if (Context == 'asistente' || Context == 'ponente') {
        // first view or empty fecha_nac
        if (!empty($submit) || empty($USER->fecha_nac)) {
            $USER->b_year = $b_year;
            $USER->b_month = $b_month;
            $USER->b_day = $b_day;

            $USER->fecha_nac = sprintf('%04d-%02d-%02d',
                                    (int)$b_year,
                                    (int)$b_month,
                                    (int)$b_day
                                );
        } else {
            // set dates from db value
            $USER->b_year = substr($USER->fecha_nac, 0, 4);
            $USER->b_month = substr($USER->fecha_nac, 5, 2);
            $USER->b_day = substr($USER->fecha_nac, 8, 2);
        }
    }
?>
