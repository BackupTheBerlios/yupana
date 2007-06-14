<?php
// basic check
if (empty($q) || empty($CFG)) {
    die;
}

// safe default value
$where = '1!=1';

// where we are?
if (Context == 'ponente') {
    preg_match('#^author/proposals/(\d+)/?$#', $q, $matches);
    $proposal_id = (!empty($matches)) ? (int) $matches[1] : 0;

    $where = 'P.id = '. $proposal_id .' AND id_ponente = '. $USER->id;
}

elseif (Context == 'main') {
    preg_match('#^general/proposals/(\d+)$#', $q, $matches);
    $proposal_id = (!empty($matches)) ? (int) $matches[1] : 0;

    $where = 'P.id = '. $proposal_id;
}

$query = '
    SELECT PO.id AS id_ponente, PO.nombrep, PO.apellidos,
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

$proposal = get_record_sql($query);

if (!empty($proposal)) {
?>

<h1>Ponencia de: <a href="<?=$CFG->wwwroot ?>/?q=general/authors/<?=$proposal->id_ponente ?>"><?=$proposal->nombrep ?> <?=$proposal->apellidos ?></a></h1>

<?php
    include($CFG->comdir . 'prop_display_info.php');

} else {
?>

<h1>Propuesta no encontrada</h1>

<div class="block"></div>

<?php if (Context == 'ponente') { ?>

<p class="error center">No se encontro la propuesta en tus registros.</p>

<?php } ?>

<p class="error center">Registros de propuesta no encontrados. Posiblemente no exista
o no tengas acceso al registro.</p>

<?php
}
?>
