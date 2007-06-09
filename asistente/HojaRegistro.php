<? 
require_once('header-common.php');

$idasistente=$_SESSION['YACOMASVARS']['asiid'];

$link=conectaBD();
$userQuery = 'SELECT * FROM asistente WHERE id="'.$idasistente.'"';
$userRecords = mysql_query($userQuery) or err("No se pudo checar el asistente".mysql_errno($userRecords));
$p = mysql_fetch_array($userRecords);

$user = get_record('asistente', 'id', $idasistente);

//
// Status 7 es Eliminado
// Seleccionamos todos los que no esten eliminados
// Tal vez podriamos mejorar esta cosa para no depender directamente de que el status siempre sea dado en el codigo
//
$userQueryP='	SELECT 	AI.reg_time, 
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
		FROM 	fecha_evento AS F, 
			ponente AS PO, 
			lugar AS L, 
			orientacion AS O, 
			inscribe AS AI, 
			evento AS E, 
			propuesta AS P, 
			evento_ocupa AS EO, 
			prop_tipo AS PT,
			prop_nivel AS N  
		WHERE 	EO.id_fecha=F.id AND 
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
$userRecordsP = mysql_query($userQueryP) or err("No se pudo listar talleres del asistente".mysql_errno($userRecords));
?>
<div id="record-page">

<h2 class="center">Hoja de Registro</h2>
<h3 class="center"><?=$user->apellidos ?> <?=$user->nombrep ?></h3>

<?php
$values = array(
    'Nombre de Usuario' => $user->login,
    'Correo Electrónico' => $user->mail,
    'Sexo' => ($user->sexo == 'M') ? 'Masculino' : 'Femenino',
    'Organización' => $user->org,
    'Estudios' => get_field('estudios', 'descr', 'id', $user->id_estudios),
    'Tipo Asistente' => get_field('tasistente', 'descr', 'id', $user->id_tasistente),
    'Ciudad' => $user->ciudad,
    'Estado' => get_field('estado', 'descr', 'id', $user->id_estado),
    );

// Inicio datos de Ponencias
    print '	<p class="yacomas_error">No tires esta hoja, te servira para asistir a cualquier Conferencia y Platica Informal, Ademas de los talleres y tutoriales que tengas registrados.<br>
		Tambien sirve para confirmar tus participaciones en eventos y extender tu constancia de asistencia.
    		</p>';

do_table_values($values);
// Fin datos de usuario
// Inicio datos de Talleres inscritos 
?>

<h2 class="center">Talleres y/o Tutoriales Inscritos</h2>

<table width=99%>
    <tr class="table-headers">
	<td>Taller/Tutorial</td>
	<td>Orientacion</td>
	<td>Fecha</td>
	<td>Hora</td>
	<td>Lugar</td>
	<td>Fecha Inscripcion</td>
	</tr>';

<?php
	$color=1;
	while ($fila = mysql_fetch_array($userRecordsP))
	{
		if ($color==1) 
		{
			$bgcolor=$color_renglon1;
			$color=2;
		}
		else 
		{
			$bgcolor=$color_renglon2;
			$color=1;
		}
		print '<tr>';
		print '</td><td bgcolor='.$bgcolor.'>'.$fila["taller"];
		print '<small> ('.$fila["prop_tipo"].')</small>';
		print '<br><small>'.$fila["nombrep"].' '.$fila["apellidos"].'</small>';
		print '</td><td bgcolor='.$bgcolor.'>'.$fila["orientacion"];
		print '</td><td bgcolor='.$bgcolor.'>'.$fila["fecha"];
		print '</td><td bgcolor='.$bgcolor.'>'.$fila["hora"].':00 - ';
		$hfin=$fila["hora"]+$fila["duracion"]-1;
		print $hfin.':50';
		print '</td><td bgcolor='.$bgcolor.'>'.$fila["nombre_lug"];
		print '</td><td bgcolor='.$bgcolor.'>'.$fila["reg_time"];
		print '</td></tr>';
		
	}
?>
	</table>

	<p id="buttons">
        <input type="button" value="Volver al menu" onClick="location.href='<?=$CFG->wwwroot ?>/asistente/menuasistente.php'" />
    </p>

</div> <!-- #recordpage -->
<?php
    do_footer();
?>
