<?php
// dummy way to check if this file is loaded by the system
if (empty($CFG) || Context != 'admin') {
    die;
}

// build data table
$table_data = array();

// date name
$dates = get_records_sql('SELECT id, fecha AS descr FROM fecha_evento ORDER BY descr');
$input_data = do_get_output('do_input_select', array('I_id_fecha', $dates, $event->id_fecha, true, '', 0, 'style=\'width:110px;\''));

$table_data[] = array(
    'Fecha de evento: *',
    $input_data,
    );

// room
if ($proposal->id_prop_tipo < 50 || $proposal->id_prop_tipo >= 100) {
    $where = 'cupo=0';
} else {
    $where = 'cupo<>0';
}

$rooms = get_records_sql('SELECT id, nombre_lug AS descr FROM lugar WHERE '.$where.' ORDER BY nombre_lug');

$input_data = do_get_output('do_input_select', array('I_id_lugar', $rooms, $event->id_lugar, 'size="30"'));

$table_data[] = array(
    'Lugar de evento: *',
    $input_data
    );

// hour
$input_data = do_get_output('do_input_number_select', array('I_hora', $CFG->def_hora_ini, $CFG->def_hora_fin-1, $event->hora));

$table_data[] = array(
    'Hora de inicio: *',
    $input_data
    );

// show tdata
do_table_input($table_data, 'left');
?>
