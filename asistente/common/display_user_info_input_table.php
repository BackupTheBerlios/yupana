<?php
//
// function to show user input info
// included on register or editing info of asistente
//

// dummy way to check if this file is loaded by the system
if (empty($CFG)) {
    die;
}

// build data table
$table_data = array();

// login
$input_data = do_get_output('do_input', array('S_login', 'text', $login, 'size="15"'));

$table_data[] = array(
    'Nombre de Usuario: *',
    $input_data,
    ' 4 a 15 caracteres'
    );

// password
$input_data = do_get_output('do_input', array('S_passwd', 'password', '', 'size="15"'));

$table_data[] = array(
    'Contraseña: *',
    $input_data,
    ' 6 a 15 caracteres'
    );

// confirm password
$input_data = do_get_output('do_input', array('S_passwd2', 'password', '', 'size="15"'));

$table_data[] = array(
    'Confirmación de Contraseña: *',
    $input_data,
    ''
    );

// first name
$input_data = do_get_output('do_input', array('S_nombrep', 'text', $nombrep, 'size="30"'));

$table_data[] = array(
    'Nombre(s): *',
    $input_data,
    ''
    );

// last name
$input_data = do_get_output('do_input', array('S_apellidos', 'text', $apellidos, 'size="30"'));

$table_data[] = array(
    'Apellidos: *',
    $input_data,
    ''
    );

// email
$input_data = do_get_output('do_input', array('S_mail', 'text', $mail, 'size="15"'));

$table_data[] = array(
    'Correo Electrónico: *',
    $input_data,
    ''
    );

// sexo
$options = array();

$option = new StdClass;
$option->id = 'M';
$option->descr = 'Masculino';

$options[] = $option;

$option = new StdClass;
$option->id = 'F';
$option->descr = 'Femenino';

$options[] = $option;

$input_data = do_get_output('do_input_select', array('C_sexo', $options, $sexo));

$table_data[] = array(
    'Sexo: *',
    $input_data,
    ''
    );

// organizacion
$input_data = do_get_output('do_input', array('S_org', 'text', $org, 'size="15"'));

$table_data[] = array(
    'Organización: &nbsp;',
    $input_data,
    ''
    );

// estudios
$options = get_records('estudios');
$input_data = do_get_output('do_input_select', array('I_id_estudios', $options, $id_estudios));

$table_data[] = array(
    'Estudios: *',
    $input_data,
    ''
    );

// tipo asistente
$options = get_records('tasistente');
$input_data = do_get_output('do_input_select', array('I_id_tasistente', $options, $id_tasistente));

$table_data[] = array(
    'Tipo de Asistente: *',
    $input_data,
    ''
    );

// ciudad
$input_data = do_get_output('do_input', array('S_ciudad', 'text', $ciudad, 'size="10"'));

$table_data[] = array(
    'Ciudad: &nbsp;',
    $input_data,
    ''
    );

// departamento
$options = get_records('estado');
$input_data = do_get_output('do_input_select', array('I_id_estado', $options, $id_estado));

$table_data[] = array(
    'Departamento: *',
    $input_data,
    ''
    );

// fecha de nacimiento
$input_data = do_get_output('do_input_birth_select', array('I_b_day', 'I_b_month', 'I_b_year', $b_day, $b_month, $b_year));

$table_data[] = array(
    'Fecha de Nacimiento: &nbsp;',
    $input_data,
    ''
    );

// show tdata
do_table_input($table_data);


