<?php
// state/departamento
//$state = get_records('estado');
$onChange = 'onChange=\'form.submit()\'';

$state = get_records_sql('SELECT D.* FROM estado D JOIN ponente SP ON SP.id_estado=D.id');

//$education = get_records('estudios');
$education = get_records_sql('SELECT ST.* FROM estudios ST JOIN ponente SP ON SP.id_estudios=ST.id');

$state_input = do_get_output('do_input_select', array('filter_id_estado', $state, $id_estado, true, '', 0, $onChange));

$education_input = do_get_output('do_input_select', array('filter_id_estudios', $education, $id_estudios, true, '', 0, $onChange));

$table_data = array();

$table_data[] = array('', 'Departamento:', 'Estudios:');
$table_data[] = array('Filtro:', $state_input, $education_input);

do_table($table_data, 'prop-filter wide');
?>
