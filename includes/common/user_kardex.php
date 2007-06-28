<?php
if (!defined('Context') || empty($CFG) || Context != 'asistente') {
    header('Location: ' . get_url());
}

$user = $USER;
?>

<p class="error">No tires esta hoja, te servira para asistir a cualquier Conferencia y Platica Informal, Ademas de los talleres y tutoriales que tengas registrados. Tambien sirve para confirmar tus participaciones en eventos y extender tu constancia de asistencia.</p>

<h1>Hoja de Registro</h1>
<h2 class="center"><?=$USER->apellidos ?> <?=$USER->nombrep ?></h2>

<?php
require($CFG->comdir . 'user_display_info.php');

// Status 7 es Eliminado
$query ='SELECT  AI.reg_time, 
            F.fecha, 
            N.descr AS nivel, 
            PO.nombrep, 
            PO.apellidos, 
            P.nombre AS taller, 
            O.descr AS orientacion, 
            EO.hora, 
            P.duracion, 
            PT.descr AS prop_tipo,
            L.nombre_lug 
        FROM '.$CFG->prefix.'fecha_evento AS F, 
            '.$CFG->prefix.'ponente AS PO, 
            '.$CFG->prefix.'lugar AS L, 
            '.$CFG->prefix.'orientacion AS O, 
            '.$CFG->prefix.'inscribe AS AI, 
            '.$CFG->prefix.'evento AS E, 
            '.$CFG->prefix.'propuesta AS P, 
            '.$CFG->prefix.'evento_ocupa AS EO, 
            '.$CFG->prefix.'prop_tipo AS PT,
            '.$CFG->prefix.'prop_nivel AS N  
        WHERE   EO.id_fecha=F.id AND 
            AI.id_evento=E.id AND 
            E.id_propuesta=P.id AND 
            AI.id_evento=EO.id_evento AND 
            P.id_orientacion=O.id AND 
            EO.id_lugar=L.id AND 
            P.id_ponente=PO.id AND 
            P.id_nivel=N.id AND 
            P.id_prop_tipo=PT.id AND
            AI.id_asistente="'.$USER->id.'" 
        GROUP BY AI.id_evento 
        ORDER BY F.fecha, AI.id_evento, EO.hora';

$records = get_records_sql($query);

if (!empty($records)) {
    $table_data = array();
    $table_data[] = array(
        'Taller/Tutorial',
        'Orientación',
        'Fecha',
        'Hora',
        'Lugar',
        'Fecha Inscripción'
        );

    foreach ($records as $record) {
        $l_taller = <<< END
{$record->tipo} <span class="small">{$record->prop_tipo}<br />
{$record->nombrep} {$record->apellidos}
END;
        $hora_fin = $record->hora + $record->duracion - 1;
        $l_hora = "{$record->hora}:00 - {$hora_fin}:50";

        $table_data[] = array(
            $l_taller,
            $record->orientacion,
            $record->fecha,
            $l_hora,
            $record->nombre_lug,
            $record->reg_time
            );
    }

    do_table($table_data, 'wide');
}

do_submit_cancel('', 'Volver al Menu', $return_url);
?>
