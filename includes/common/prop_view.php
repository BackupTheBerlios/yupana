<?php
// basic check
if (empty($q) || empty($CFG)) {
    die;
}

if (Context == 'main') {
    $q = optional_param('q');
    $return_url = optional_param('return');

    preg_match('#^general/proposals/(\d+)$#', $q, $matches);
    $proposal_id = (int) $matches[1];
}

$query = '
    SELECT PO.id AS id_ponente, PO.nombrep, PO.apellidos,
    P.nombre AS ponencia,
    P.duracion,
    P.id_status,
    P.resumen,
    P.reqasistente,
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
    WHERE P.id = '.$proposal_id;

$proposal = get_record_sql($query);

if (!empty($proposal)) {
?>

<h1>Ponencia de: <a href="<?=$CFG->wwwroot ?>/?q=general/authors/<?=$proposal->id_ponente ?>&return=<?=$return_url ?>"><?=$proposal->nombrep ?> <?=$proposal->apellidos ?></a></h1>

<?php
    $values = array(
        'Nombre de Ponencia' => $proposal->ponencia,
        'Nivel' => $proposal->nivel,
        'Tipo de Propuesta' => $proposal->tipo,
        'Orientación' => $proposal->orientacion,
        'Duración' => sprintf('%d Hrs.', $proposal->duracion),
        'Status' => $proposal->status
        );

    // if it's schedule merge date info
    if ($proposal->id_status == 8) {
        $query = '
            SELECT FE.fecha, L.nombre_lug AS lugar, EO.hora
            FROM evento E
            JOIN evento_ocupa EO ON EO.id_event = E.id
            JOIN fecha_evento FE ON FE.id = E.id_fecha
            JOIN lugar L ON L.id = E.id_lugar
            WHERE E.id_propuesta = '.$proposal_id;

        $schedule = get_record_sql($query);
        $endhour = $schedule->hora + $proposal->duracion -1;
        $schedule->time = sprintf('%02d:00 - %02d:50', $schedule->hora, $endhour);

        $values = array_merge($values, array(
                'Fecha' => $schedule->fecha,
                'Lugar' => $schedule->lugar,
                'Hora' => $schedule->time
            ));
    }

    // show proposal info
    do_table_values($values, 'narrow');

    // show proposal aditional info
    $values = array(
        'Resumen' => $proposal->resumen,
        'Prerequisitos del Asistente' => $proposal->reqasistente
        );
    do_table_values($values, 'narrow');

} else {
?>

<p class="error center">Registros de propuesta no encontrados.</p>

<?php
}
?>
