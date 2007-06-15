<?php

$values = array();

// multiple users per mail
$desc = 'Un usuario por email';
$config = get_config('unique_mail');
$input = do_get_output('do_input_yes_no', array('unique_mail', $config->value));

$values[$desc] = $input;

// max limit to participants in workshops/tutorials
$desc = 'Número máximo de asistentes a talleres/tutoriales';
$config = get_config('limite');
$input = do_get_output('do_input', array('limite', 'text', $config->value, 'size="3"'));

$values[$desc] = $input;

// start time of the event
$desc = 'Hora de inicio';
$config = get_config('def_hora_ini');
$input = do_get_output('do_input_number_select', array('def_hora_ini', 0, 23, $config->value, false));

$values[$desc] = $input;

// end time of the event
$desc = 'Hora de fin';
$config = get_config('def_hora_fin');
$input = do_get_output('do_input_number_select', array('def_hora_fin', 0, 23, $config->value, false));

$values[$desc] = $input;


// notify by email flag
$desc = 'Enviar mensajes por email';
$config = get_config('send_mail');
$input = do_get_output('do_input_yes_no', array('send_mail', $config->value));

$values[$desc] = $input;


// smtp server host
$desc = 'Servidor SMTP';
$config = get_config('smtp');
$input = do_get_output('do_input', array('smtp', 'text', $config->value, 'size="30"'));

$values[$desc] = $input;

// clean url input select
$desc = 'Usar URLs limpios';
$config = get_config('clean_url');

// clean url test
$clean_url_test_url = str_replace('?q=', '', get_url('admin/config'));
$clean_url_test = sprintf('<br/><small><a title="%s" href="'.$clean_url_test_url.'">%s</small>', 'Necesitar tener habilitado el módulo mod_rewrite', 'Habilitar');

$desc .= $clean_url_test;

//can use clean urls?
if (preg_match('#\?q=#', $_SERVER['REQUEST_URI'])) {
    $disabled = 'disabled="disabled"';
} else {
    $disabled = '';
}

$input = do_get_output('do_input_yes_no', array('clean_url', $config->value, 'Yes', 'No', $disabled));

$values[$desc] = $input;


do_table_values($values, 'narrow');
