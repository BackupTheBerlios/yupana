<?php
    require_once('header-common.php');

    $request_uri = $_SERVER['REQUEST_URI'];
?>

<h1>Listado de ponencias eliminadas</h1>

<?php
$query = 'SELECT P.act_time, A.login, P.id AS id_ponencia, P.nombre AS ponencia,
        P.id_prop_tipo, PT.descr,P.id_ponente, PO.nombrep, PO.apellidos, S.descr AS status 
    FROM administrador AS A, propuesta AS P, ponente AS PO, prop_status AS S, 
        prop_tipo AS PT
    WHERE   P.id_administrador=A.id AND 
            P.id_ponente=PO.id AND 
            P.id_status=S.id AND 
            P.id_prop_tipo=PT.id AND
            id_status=7 
    ORDER BY P.id_ponente,P.act_time';

$props = get_records_sql($query);

if (!empty($props)) {
    $stats = get_records_select('prop_status', 'id < 7');

    $table_data = array();
    $table_data[] = array(
        'Ponencia',
        'Modificado por',
        'Fecha de modif.',
        'Tipo',
        'Ponente');

    foreach ($props as $prop) {
        $l_ponencia = <<< END
<a class="azul" href="Vponencia.php?vopc={$prop->id_ponente} {$prop->id_ponencia} {$request_uri}">{$prop->ponencia}</a>
END;
        $l_ponente = <<< END
<a class="azul" href="Vponente.php?vopc={$prop->id_ponente} {$request_uri}">{$prop->nombrep} {$prop->apellidos}</a>
END;
        $l_stats = '';
        foreach ($stats as $stat) {
            $l_stats .= <<< END
<a class="small verde" href="act_ponencia.php?vact={$prop->id_ponencia} {$stat->id} {$request_uri}">{$stat->descr}</a> | 
END;
        }

        $l_ponencia .= '<br />' . $l_stats;

        $table_data[] = array(
            $l_ponencia,
            $prop->login,
            $prop->act_time,
            $prop->descr,
            $l_ponente);
    }

    do_table($table_data, 'wide');

} else {
?>

<p class="error center">No se encontro ninguna ponencia eliminada.</p>

<?php } ?>

<p id="buttons">
    <input type="button" value="Volver al menu" onClick="location.href='<?=$CFG->wwwroot ?>/admin/menuadmin.php#admin'" />
</p>

<?php
do_footer();
?>
