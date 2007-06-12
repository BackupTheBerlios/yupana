<?php
// dummy check
if (empty($CFG)) {
    die;
}

if (empty($not_found_message)) {
    $not_found_message = "No se encontro ninguna propuesta registrada.";
}

$query = '
    SELECT P.id AS id_ponencia,
        P.nombre AS ponencia,
        P.id_prop_tipo,
        P.id_ponente,
        PO.nombrep,
        PO.apellidos,
        PT.descr AS prop_tipo,
        S.descr AS status
    FROM propuesta P
    JOIN ponente PO ON P.id_ponente = PO.id
    JOIN prop_tipo PT ON P.id_prop_tipo = PT.id
    JOIN prop_status S ON P.id_status = PO.id
    WHERE P.id_status != 7
    ORDER BY P.id_prop_tipo, P.id_ponente, P.reg_time';

$records = get_records_sql($query);

if (!empty($records)) {
    // build data table
    $table_data = array();

    // table headers
    $table_data[] = array('Ponencia', 'Tipo', 'Estado');

    foreach ($records as $record) {
        $l_ponencia = <<< END
<a class="proposal" href="{$CFG->wwwroot}/?q=proposals/view/{$record->id_ponencia}&return={$request_uri}">{$record->ponencia}</a>
<br />
<a class="author" href="{$CFG->wwwroot}/?q=authors/view/{$record->id_ponencia}&return={$request_uri}">{$record->nombrep} {$record->apellidos}</a>
END;

        $table_data[] = array(
            $l_ponencia,
            $record->prop_tipo,
            $record->status
            );
    }

    do_table($table_data, 'wide');

} else {
?>

<p class="error center"><?=$not_found_message ?></p>

<?php 
}
?>
