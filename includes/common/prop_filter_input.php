<?php
// dont show organization events
$prop_type = get_records_select('prop_tipo', 'id <= 100');

// get all tracks
$tracks = get_records('orientacion');

// dont show deleted status
$status = get_records_select('prop_status', 'id != 7');

$prop_type_input = do_get_output('do_input_select', array('filter_id_prop_tipo', $prop_type, $id_prop_tipo, true, '', 0, 'onChange="form.submit()"'));
$tracks_input = do_get_output('do_input_select', array('filter_id_orientacion', $tracks, $id_orientacion, true, '', 0, 'onChange="form.submit()"'));
$status_input = do_get_output('do_input_select', array('filter_id_status', $status, $id_status, true, '', 0, 'onChange="form.submit()"'));

$table_data = array();

//headers
$table_data[] = array('', 'Tipo:', 'Orientacion:', 'Estado:');
$table_data[] = array('Filtro:', $prop_type_input, $tracks_input, $status_input);

do_table($table_data, 'prop-filter wide');
?>
