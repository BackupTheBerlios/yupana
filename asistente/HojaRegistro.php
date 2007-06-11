<?php
require_once('header-common.php');
$idasistente=$_SESSION['YACOMASVARS']['asiid'];
?>

<p class="error">No tires esta hoja, te servira para asistir a cualquier Conferencia y Platica Informal, Ademas de los talleres y tutoriales que tengas registrados. Tambien sirve para confirmar tus participaciones en eventos y extender tu constancia de asistencia.</p>

<h1>Hoja de Registro</h1>

<?php 
$user = get_record('asistente', 'id', $idasistente);
?>

<h2 class="center"><?=$user->apellidos ?> <?=$user->nombrep ?></h2>

<?php

$hoja_registro = true;

require('common/nasistente_display_values.php');

// Fin datos de usuario

// Inicio datos de Talleres inscritos 

// Status 7 es Eliminado
// Seleccionamos todos los que no esten eliminados
// Tal vez podriamos mejorar esta cosa para no depender directamente de que el status siempre sea dado en el codigo

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
        FROM    fecha_evento AS F, 
            ponente AS PO, 
            lugar AS L, 
            orientacion AS O, 
            inscribe AS AI, 
            evento AS E, 
            propuesta AS P, 
            evento_ocupa AS EO, 
            prop_tipo AS PT,
            prop_nivel AS N  
        WHERE   EO.id_fecha=F.id AND 
            AI.id_evento=E.id AND 
            E.id_propuesta=P.id AND 
            AI.id_evento=EO.id_evento AND 
            P.id_orientacion=O.id AND 
            EO.id_lugar=L.id AND 
            P.id_ponente=PO.id AND 
            P.id_nivel=N.id AND 
            P.id_prop_tipo=PT.id AND
            AI.id_asistente="'.$idasistente.'" 
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
?>

<p id="buttons">
    <input type="button" value="Volver al menu" onClick="location.href='<?=$CFG->wwwroot ?>/asistente/menuasistente.php'" />
</p>

<?php
do_footer();
?>
