<?php
    require_once('header-common.php');
	
    $submit = optional_param('submit');
    $idfecha = optional_param('idfecha', 0, PARAM_INT);
    $request_uri = $_SERVER['REQUEST_URI'];
?>

<h1>Eliminar Fecha</h1>

<p class="notice center">Esta acción eliminará la fecha del programa de eventos y todos los eventos registrados en esta fecha.<br />
Los eventos regreseran al estado de Aceptada, las inscripciones de los asistentes al evento serán eliminadas.</p>

<?php
if ($submit == "Eliminar") {

    // check if record exists
    if ($rs = record_exists('fecha_evento', 'id', $idfecha)) {

        if ($rs = record_exists('evento_ocupa', 'id_fecha', $idfecha)) {

            $query = 'SELECT id_evento
                FROM evento_ocupa
                WHERE id_fecha='.$idfecha.'
                GROUP BY id_evento';

            // select events within this date
            $events = get_records_sql($query);

            if (!empty($events)) {

               // Update ocurrence of every event
                foreach ($events as $event) {
                    $id_propuesta = get_field('evento', 'id_propuesta', 'id', $event->id_evento);

                    // our record to update
                    $propuesta = new StdClass;
                    $propuesta->id = $id_propuesta;
                    $propuesta->id_status = 5; // Acepted

                    if (!$rs = update_record('propuesta', $propuesta)) {
                        //TODO: debug error
                    }

                    if (!$rs = delete_records('evento', 'id', $event->id_evento)) {
                        //TODO: debug error
                    }

                    if (!$rs = delete_records('inscribe', 'id_evento', $event->id_evento)) {
                        //TODO: debug error
                    }
                }
            }

            if (!$rs = delete_records('evento_ocupa', 'id_fecha', $idfecha)) {
                //TODO: debug error
            }
        }
 
        // finally delete fecha
        if (!$rs = delete_records('fecha_evento', 'id', $idfecha)) {
            //TODO: debug error
        }
?>

    <p>La fecha ha sido eliminada del programa.<br />
    Los espacios que ocupaban en los talleres los asistentes que estaban inscritos han sido liberados
    Las ponencias registradas han sido cambiado en status de Aceptadas en espera de nueva asigación de lugar y fecha
    para que los asistentes puedan inscribirse a ella.
    </p>
    <p>Si tienes preguntas o no sirve adecuadamente la página, por favor contacta a <a href="mailto:<?=$CFG->adminmail ?>">Administración <?=$CFG->conference_name ?>.</a>

<?php } else { ?>

<p class="error center">La fecha no existe.</p>

<?php } ?>

<p id="buttons">
    <input type="button" value="Volver al listado" onClick="location.href='<?=$CFG->wwwroot ?>/admin/admin.php?opc=dates/list'" />
</p>

<?php
    do_footer();
    exit;
    //END
}
//
// print the form
//
$date = get_record('fecha_evento', 'id', $idfecha);

if (!empty($date)) {
   $datetime = strftime_caste('%A %d de %B', strtotime($date->fecha));
?>

<h2 class="center"><?=$datetime ?></h2>

<?php if (!empty($date->descr)) { ?>

<h3 class="center"><?=$date->descr ?></h3>

<?php  }

    // print events of the day if exists
    $query = 'SELECT EO.id_lugar, L.cupo, EO.id_fecha, EO.id_evento,
        E.id_propuesta, P.nombre, P.id_prop_tipo,PT.descr AS prop_tipo,
        EO.hora, P.duracion, P.id_ponente, PO.nombrep, PO.apellidos,
        L.nombre_lug
    FROM evento AS E, propuesta AS P, evento_ocupa AS EO, ponente AS PO,
        prop_tipo AS PT, lugar AS L
    WHERE E.id_propuesta=P.id AND
        E.id=EO.id_evento AND
        P.id_ponente=PO.id AND
        EO.id_lugar=L.id AND
        P.id_prop_tipo=PT.id AND
        EO.id_fecha="'.$date->id.'"
    GROUP BY id_evento ORDER BY EO.id_fecha,EO.hora';

    $events = get_records_sql($query);

    if (!empty($events)) {
        $table_data = array();
        $table_data = array('Ponencia', 'Tipo', 'Hora', 'Lugar', 'Cupo Disp.', '');

        foreach ($events as $event) {
            $l_ponencia = <<< END
<a class="azul" href="Vponencia.php?vopc={$event->id_ponente} {$event->id_propuesta} {$request_uri}">{$event->nombre}</a><br />
<a class="ponente" href="Vponente.php?vopc={$event->id_ponente} {$request_uri}">{$event->nombrep} {$event->apellidos}</a>
END;
            $hora_fin = $event->hora + $event->duracion - 1;
            $l_hora = "{$event->hora}:00 - {$hora_fin}:50";

            if ($event->id_prop_tipo >= 50 && $event->id_prop_tipo < 100) {
                $inscritos = count_records('inscribe', 'id_evento', $event->id_evento);
                $l_disp = $event->cupo - $inscritos;
                $l_asistentes = <<< END
<a class="verde" href="Lasistentes-reg.php?vopc={$event->id_evento} {$request_uri}">Asistentes</a>
END;
            } else {
                $l_disp = '--';
                $l_asistentes = '--';
            }

            $table_data[] = array($l_ponencia, $event->prop_tipo, $l_hora, $event->nombre_lug, $l_disp, $l_asistentes);
        }

        do_table($table_data, 'wide');

    } else {
?>

<p class="error center">Esta fecha no tiene ningún evento registrado.</p>

<?php
    }
} else {
    $no_submit = true; 
?>

<p class="error center">No se encontro la fecha especificada.</p>

<?php } ?>

<form method="POST" action="<?=$request_uri ?>">
    <p id="buttons">
<?php if (empty($no_submit)) { ?>
        <input type="submit" name="submit" value="Eliminar" />
<?php } ?>
        <input type="button" value="Cancelar" onClick="location.href='<?=$CFG->wwwroot ?>/admin/admin.php?opc=dates/list'" />
    </p>
</form>

<?php
do_footer();
?>
