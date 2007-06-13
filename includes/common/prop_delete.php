<?php
// dummy check
if (empty($q) || empty($CFG)) {
    die;
}

preg_match('#^author/proposals/(\d+)/delete$#', $q, $matches);
$proposal_id = (!empty($matches)) ? (int) $matches[1] : 0;

$submit = optional_param('submit');

$where = "P.id = {$proposal_id} AND PO.id = {$USER->id}";

$query = '
    SELECT P.id, PO.id AS id_ponente, PO.nombrep, PO.apellidos,
    P.nombre AS ponencia,
    P.duracion,
    P.id_status,
    P.resumen,
    P.reqasistente,
    P.reqtecnicos,
    P.id_administrador,
    PN.descr AS nivel,
    PT.descr AS tipo,
    O.descr AS orientacion,
    S.descr AS status
    FROM propuesta P
    JOIN ponente PO ON P.id_ponente = PO.id
    JOIN prop_nivel PN ON P.id_nivel = PN.id
    JOIN prop_tipo PT ON P.id_prop_tipo = PT.id
    JOIN orientacion O ON P.id_orientacion = O.id
    JOIN prop_status S ON P.id_status = S.id
    WHERE '. $where;

?>

<h1>Eliminar propuesta</h1>

<?php
//Check proposal owner
$proposal = get_record_sql($query);

//check owner and status, dont delete acepted, scheduled or deleted¿?
if (!empty($proposal) && $proposal->id_status < 5)  {

    if (empty($submit)) {
        // confirm delete
?>

<form method="POST" action="";

<?php
        // flag to not show resume and extra info
        $prop_noshow_resume = true;
        
        include($CFG->incdir . 'common/prop_display_info.php');
        do_submit_cancel('Eliminar', 'Cancelar', $home_url);
?>

</form>

<?php
    } else {
        // delete!
        // (really change status to deleted)

        // only update these values
        $prop = new StdClass;
        $prop->id = $proposal->id;
        $prop->id_status = 7;

        if (!$rs = update_record('propuesta', $prop)) {
            show_error('Ocurrio un error al eleminar el registro.');
        } else {
?> 

<div class="block"></div>

<p class="center">La propuesta fue eliminada exitosamente.</p>

<?php 
        }

        do_submit_cancel('', 'Continuar', $home_url);
    }

} else {
?>

<h1>Propuesta no encontrada</h1>

<div class="block"></div>
<p class="center">Registros de propuesta no encontrados. Posiblemente no existan o no tengas acceso para eliminar la propuesta.</p>

<?php
    do_submit_cancel('', 'Regresar', $home_url);
}
?>



