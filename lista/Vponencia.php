<?php
require_once(dirname(dirname(__FILE__)) . '/includes/lib.php');

$idponente = optional_param('ponente', 0, PARAM_INT);
$idponencia = optional_param('ponencia', 0, PARAM_INT);
$return_url = optional_param('return');

do_header();

$user = get_record('ponente', 'id', $idponente);
$ponencia = get_record('propuesta', 'id', $idponencia, 'id_ponente', $idponente);

if (!empty($user) && !empty($ponencia)) {
?>

<h1>Ponencia de: <a href="Vponente.php?ponente=<?=$user->id ?>&return=<?=$return_url ?>"><?=$user->nombrep ?> <?=$user->apellidos ?></a></h1>

<?php
    $desc_nivel = get_field('prop_nivel', 'descr', 'id', $ponencia->id_nivel);
    $tipo_prop = get_field('prop_tipo', 'descr', 'id', $ponencia->id_prop_tipo);
    $desc_orientacion = get_field('orientacion', 'descr', 'id', $ponencia->id_orientacion);
    $desc_duracion = sprintf('%02d Hrs.', $ponencia->duracion);
    $desc_status = get_field('prop_status', 'descr', 'id', $ponencia->id_status);

    $datos_ponencia = array(
            'Nombre de Ponencia' => $ponencia->nombre,
            'Nivel' => $desc_nivel,
            'Tipo de Propuesta' => $tipo_prop,
            'Orientación' => $desc_orientacion,
            'Duración' => $desc_duracion,
            'Status' => $desc_status,
        );

    // If it's published, then merge date info
    if ($ponencia->id_status == 8) {
        $id_evento = get_field('evento', 'id', 'id_propuesta', $idponencia);
        $evento = get_record('evento_ocupa', 'id_event', $id_evento);

        $desc_fecha = get_field('fecha_evento', 'fecha', 'id', $evento->id_fecha);
        $desc_lugar = get_field('lugar', 'nombre_lug', 'id', $evento->id_lugar);

        $hora_fin = $evento->hora + $ponencia->duracion - 1;
        $desc_hora = "{$evento->hora}:00 - {$hora_fin}:50";

        $datos_pub = array(
            'Fecha' => $desc_fecha,
            'Lugar' => $desc_lugar,
            'Hora' => $desc_hora
            );

        $datos_ponencia = array_merge($datos_ponencia, $datos_pub);
    }

    $datos_resumen = array();
    $datos_resumen['Resumen'] = $ponencia->resumen;
    $datos_resumen['Prerequisitos del Asistente'] = $ponencia->reqasistente;

    do_table_values($datos_ponencia, 'narrow');
    do_table_values($datos_resumen, 'narrow');

} else {
?>
    
<p class="error center">Usuario o ponencia no encontrado.</p>

<?php
}

do_submit_cancel('', 'Regresar', $return_url);
do_footer();
?>
