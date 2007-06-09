<?php
    require_once('header-common.php');

    $idfecha = optional_param('idfecha', 0, PARAM_INT);
    $request_uri = $_SERVER['REQUEST_URI'];
?>

<h1>Listado de eventos por fecha</h1>

<?php
$date = get_record('fecha_evento', 'id', $idfecha);

if (!empty($date)) {
    $datetime = strftime_caste('%A %d de %B', strtotime($date->fecha));
?>

<h2 class="center"><?=$datetime ?></h2>

<?php if (!empty($date->descr)) { ?>

<h3 class="center"><?=$date->descr ?></h2>

<?php }

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
?>    

<p class="error center">No se encontro la fecha especificada.</p>

<?php } ?>

<p id="buttons">
<input type="button" value="Volver" onClick="location.href='<?=$CFG->wwwroot ?>/admin/admin.php?opc=12'" />
</p>

<?php
    do_footer();
?>
