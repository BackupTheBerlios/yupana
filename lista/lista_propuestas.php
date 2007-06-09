<?php
    require('../includes/lib.php');
	do_header();
?>

<h1>Lista de propuestas enviadas</h1>

<?php
//
// Status 7 es Eliminado
// Seleccionamos todos los que no esten eliminados ni esten programados
// Tal vez podriamos mejorar esta cosa para no depender directamente de que el status siempre sea dado en el codigo
//
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
<a class="azul" href="Vponencia.php?vopc={$prop->id_ponente}%20{$prop->id_ponencia}%20{$_SERVER['REQUEST_URI']}"> {$prop->ponencia} </a>
<br />
<a class="ponente" href="Vponente.php?vopc={$prop->id_ponente} {$_SERVER['REQUEST_URI']}">{$prop->nombrep} {$prop->apellidos}</a>
END;

        $table_data[] = array($tponencia, $prop->prop_tipo, $prop->status);
    }

    do_table($table_data, 'wide');

} else {
?>

<p class="error center">Todavía no se registraron ponencias</p>

<?php } ?>

<p id="buttons">
    <input type="button" value="Regresar" onClick="location.href='../'" />
</p>

<?	do_footer(); ?> 
