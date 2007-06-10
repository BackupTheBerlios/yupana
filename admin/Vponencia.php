<?php
require_once('header-common.php');

$vopc = optional_param('vopc');

$tok = strtok ($vopc, " ");
$idponente = (int) $tok;

$tok = strtok (" ");
$idponencia = (int) $tok;

$tok = strtok (" ");
$regresa='';    
while ($tok) {
    $regresa .=' '.$tok;
    $tok=strtok(" ");
}

$user = get_record('ponente', 'id', $idponencia);
$ponencia = get_record('propuesta', 'id', $idponencia, 'id_ponente', $idponente);

if (!empty($user) && !empty($ponencia)) {
?>

<h1>Ponencia de: <a href="Vponente.php?vopc=<?=$user->id ?> <?=$_SERVER['REQUEST_URI'] ?>"><?=$user->nombrep ?> <?=$user->apellidos ?></a></h1>

<?php
    $desc_nivel = get_field('prop_nivel', 'descr', 'id', $ponencia->id_nivel);
    $tipo_prop = get_field('prop_tipo', 'descr', 'id', $ponencia->id_prop_tipo);
    $desc_orientacion = get_field('orientacion', 'descr', 'id', $ponencia->id_orientacion);
    $desc_duracion = sprintf('%02d Hrs.', $ponencia->duracion);
    $desc_status = get_field('prop_status', 'descr', 'id', $ponencia->id_status);
    $desc_archivo = (empty($ponencia->nombreFile) ? 'El usuario no ha enviado ningún archivo' : $prop->nombreFile);

    $mod_by = get_field('administrador', 'login', 'id', $ponencia->id_administrador);
    $mod_by = (empty($mod_by)) ? 'Usuario' : $mod_by;

    $datos_ponencia = array(
        'Nombre de Ponencia' => $ponencia->nombre,
        'Nivel' => $desc_nivel,
        'Tipo de Propuesta' => $tipo_prop,
        'Orientación' => $desc_orientacion,
        'Duración' => $desc_duracion,
        'Status' => '<strong>' . $desc_status . '</strong>',
        'Archivo' => $desc_archivo,
        'Fecha de registro' => $ponencia->reg_time,
        'Fecha de actualización' => $ponencia->act_time,
        'Actualizado por' => '<strong>' . $mod_by . '</strong>'
        );

    if ($ponencia->id_status == 8) { // programmed
//        $query = 'SELECT EO.hora, FE.fecha, L.nombre_lug
        $query = 'SELECT * FROM evento_ocupa EO
                JOIN evento E ON E.id = EO.id_evento
                JOIN fecha_evento FE ON FE.id = EO.id_fecha
                JOIN lugar L ON L.id = EO.id_lugar
                WHERE E.id_propuesta="'.$idponencia;

        $pub = get_record_sql($query);

        $hora_fin = $pub->hora + $ponencia->duracion - 1;
        $desc_hora = $pub->hora.':00 - '.$hora_fin.':50';

        $datos_pub = array(
            'Fecha' => $pub->fecha,
            'Lugar' => $pub->nombre_lug,
            'Hora' => $desc_hora
            );

        $datos_ponencia = array_merge($datos_ponencia, $datos_pub);
    }

    $datos_resumen = array();
    $datos_resumen['Resumen'] = $ponencia->resumen;

    if (!empty($ponencia->reqtecnicos)) {
        $datos_resumen['Requisitos técnicos'] = $ponencia->reqtecnicos;
    }

    $datos_resumen['Prerequisitos del Asistente'] = $ponencia->reqasistente;

    do_table_values($datos_ponencia, 'narrow');
    do_table_values($datos_resumen, 'narrow');

} else {
?>

<p class="error center">Usuario o ponencia no encontrado.</p>

<?php } ?>

<p id="buttons">
    <input type="button" value="Regresar" onClick="location.href='<?=$regresa ?>'" />
</p>

<?php
do_footer();
?>
