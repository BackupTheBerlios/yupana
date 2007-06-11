<?php
require_once(dirname(dirname(__FILE__)). '/includes/lib.php');

$request_uri = $_SERVER['REQUEST_URI'];

do_header();
?>

<h1>Lista de propuestas enviadas</h1>

<?php
// Status 7 es Eliminado
$query ='SELECT P.id AS id_ponencia, P.nombre AS ponencia, 
                P.id_prop_tipo, P.id_ponente, PO.nombrep,
                PO.apellidos, PT.descr AS prop_tipo, S.descr AS status 
        FROM propuesta AS P, ponente AS PO, prop_status AS S, prop_tipo AS PT
        WHERE P.id_ponente=PO.id AND P.id_status=S.id 
            AND P.id_prop_tipo=PT.id AND id_status != 7 
        ORDER BY P.id_prop_tipo,P.id_ponente,P.reg_time';

$props = get_records_sql($query);

if (!empty($props)) {
    $table_data = array();
    $table_data[] = array('Ponencia', 'Tipo', 'Estado');

    foreach($props as $prop)
    {
        $tponencia = <<< END
<a class="azul" href="Vponencia.php?ponente={$prop->id_ponente}&ponencia={$prop->id_ponencia}&return={$request_uri}"> {$prop->ponencia} </a>
<br />
<a class="ponente" href="Vponente.php?ponente={$prop->id_ponente}&return={$request_uri}">{$prop->nombrep} {$prop->apellidos}</a>
END;

        $table_data[] = array($tponencia, $prop->prop_tipo, $prop->status);
    }

    do_table($table_data, 'wide');

} else {
?>

<p class="error center">Todavía no se registraron ponencias</p>

<?php 
}

do_submit_cancel('', 'Regresar', $CFG->wwwroot);
do_footer();
?>
