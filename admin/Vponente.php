<? 
require_once('header-common.php');

$vopc = optional_param('vopc');

$tok = strtok ($vopc," ");
$idponente=(int)$tok;

$tok = strtok (" ");
$regresa='';
	while ($tok) {
		$regresa .=' '.$tok;
		$tok=strtok(" ");
	}

$link=conectaBD();

//$userQuery = 'SELECT * FROM ponente WHERE id="'.$idponente.'"';
//$userRecords = mysql_query($userQuery) or err("No se pudo checar el ponente".mysql_errno($userRecords));

$user = get_record('ponente', 'id', $idponente);

//$p = mysql_fetch_array($userRecords);

//
// Status 7 es Eliminado
// Seleccionamos todos los que no esten eliminados
// Tal vez podriamos mejorar esta cosa para no depender directamente de que el status siempre sea dado en el codigo
//
$userQueryP = '	SELECT 	* 
		FROM 	propuesta 
		WHERE 	id_ponente="'.$idponente.'" AND 
			id_status!=7';
$userRecordsP = mysql_query($userQueryP) or err("No se pudo listar ponencias".mysql_errno($userRecords));

$props = get_records_select('propuesta', 'id_ponente=? AND id_status!=?', array($idponente,7));

//$msg='Datos de ponente <br><small>-- '.$p['nombrep'].' '.$p['apellidos'].' --</small><hr>';

?>
<h1>Datos de ponente</h1>
<h2><?=$user->nombrep ?> <?=$user->apellidos ?></h2>
<?php

$values = array(
    'Correo Electrónico' => $user->mail,
    'Sexo' => ($user->sexo == 'M') ? 'Masculino' : 'Femenino',
    'Organización' => $user->org,
    'Estudios' => get_field('estudios', 'descr', 'id', $user->id_estudios),
    'Título' => $user->titulo,
    'Domicilio' => $user->domicilio,
    'Telefono' => chunk_split($user->telefono, 2),
    'Ciudad' => $user->ciudad,
    'Estado' => get_field('estado', 'descr', 'id', $user->id_estado),
    'Fecha de Nacimiento' => $user->fecha_nac,
    'Resumen Curricular' => $user->resume
    );
?>
<div id='ponente-details'>
<?php
    do_table_values($values);
?>
</div>

    <h2>Ponencias registradas</h2>

<table class="wide">
    <tr class="table-headers">
        <td>Ponencia</td>
        <td>Tipo</td>
        <td>Status</td>
        <td>Archivo</td>
	</tr>
<?php

    if (!empty($props)) {
        $trclass='even';

        foreach($props as $prop) {
?>
    <tr class="<?=($trclass == 'even') ? 'even' : 'odd' ?>">
        <td><a class="azul" href="Vponencia?vopc=<?=$idponente ?> <?=$prop->id ?> <?=$_SERVER['REQUEST_URI'] ?>"><?=$prop->nombre ?></a></td>

        <td><?=get_field('prop_tipo', 'descr', 'id', $prop->id_prop_tipo) ?></td>
        
        <td><?=get_field('prop_status', 'descr', 'id', $prop->id_status) ?></td>
        <td><?=(empty($prop->nombreFile)) ? '<em>No</em>' : '<img src="'.$CFG->wwwroot.'/images/checkmark.gif" />' ?></td>

<?php
            // toggle trclass
            $trclass = ($trclass == 'even') ? 'odd' : 'even';
        }
    }
?>

</table>

<p id="buttons">
    <input type="button" value="Volver al menu" onClick="location.href='<?=$CFG->wwwroot ?>/admin/menuadmin.php#ponencias'" />

<?php
    do_footer();
?>
