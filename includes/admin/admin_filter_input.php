<?php
// only filter by tadmin
$admin_types = get_records('tadmin');

$admin_input = do_get_output('do_input_select', array('filter_id_tadmin', $admin_types, $id_tadmin, true, '', 0, 'onChange=\'form.submit()\''));

$table_data = array();
$table_data[] = array('', 'Tipo:');

$table_data[] = array('Filtro:', $admin_input);

do_table($table_data, 'prop-filter wide');
?>

