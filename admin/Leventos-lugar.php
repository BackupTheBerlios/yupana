<?php
    require_once('header-common.php');

    $idlugar = optional_param('idlugar', 0, PARAM_INT);
    $request_uri = $_SERVER['REQUEST_URI'];
?>

<h1>Listado de eventos por lugar</h1>

<?php
$room = get_record('lugar', 'id', $idlugar);

if (!empty($room)) {
    $tipo_room = ($room->cupo > $CFG->limite) ? 'Salón para Conferencias' : 'Aula para Talleres y/o Tutoriales';

    $values = array(
        'Nombre' => $room->nombre_lug,
        'Ubicación' => $room->ubicacion,
        'Tipo' => $tipo_room
    );

    do_table_values($values, 'narrow');

    $query = 'SELECT  E.id_propuesta, P.id_ponente, P.duracion, L.cupo,
            EO.id_evento, EO.hora, F.fecha, P.nombre, PO.nombrep,
            PO.apellidos, P.id_prop_tipo,PT.descr AS prop_tipo
        FROM 	ponente AS PO, propuesta AS P, prop_tipo AS PT,
            evento AS E, lugar AS L, evento_ocupa AS EO,fecha_evento AS F  
        WHERE EO.id_lugar=L.id AND 
            EO.id_fecha=F.id AND 
            EO.id_evento=E.id AND 
            E.id_propuesta=P.id AND 
            P.id_ponente=PO.id AND
            P.id_prop_tipo=PT.id AND 
            L.id='.$idlugar.'
        GROUP BY id_evento 
        ORDER BY F.fecha,EO.hora'; 

    $events = get_records_sql($query);

    if (!empty($events)) {
        $table_data = array();
        $table_data = array('Ponencia', 'Fecha', 'Hora', 'Cupo Disp.', '');

        foreach ($events as $event) {
            $l_ponencia = <<< END
<a class="azul" href="Vponencia.php?vopc={$event->id_ponente} {$event->id_propuesta} {$request_uri}">{$event->nombre}</a><br />
<a class="ponente" href="Vponente.php?vopc={$event->id_ponente} {$request_uri}">{$event->nombrep} {$event->apellidos}</a>
END;
            $l_fecha = strftime_caste("%A %d de %B", strtotime($event->fecha));
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

            $table_data[] = array($l_ponencia, $l_fecha, $l_hora, $l_disp, $l_asistentes);
        }

        do_table($table_data, 'wide');

    } else {
?>

<p class="error center">Este lugar no tiene ningún evento registrado.</p>

<?php
    }
} else {
?>

<p class="error center">No se encontro el lugar especificado.</p>

<?php } ?>

<p id="buttons">
    <input type="button" value="Volver al menu" onClick="location.href='<?=$CFG->wwwroot ?>/admin/admin.php?opc=5'" />
</p>

<?php
    do_footer();
?>
