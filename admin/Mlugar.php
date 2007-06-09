<?php
    require_once('header-common.php');
    $submit = optional_param('submit');
	$idlugar = optional_param('idlugar', 0, PARAM_INT);
    $nombre_lugar = strtoupper(optional_param('S_nombre_lug'));
    $ubicacion = optional_param('S_ubicacion');
    $cupo = optional_param('I_cupo', 0, PARAM_INT);
?>

<h1>Modificar lugares para ponencias</h1>

<?php
if ($submit == "Actualizar") {

  # do some basic error checking
  $errmsg = array();

  // Verificar si todos los campos obligatorios no estan vacios
  if (empty($nombre_lugar) || empty($ubicacion)) {

	$errmsg[] = "Verifica que los datos obligatorios los hayas introducido correctamente.";

  }

  if (!empty($cupo)) {

  	if ($cupo > $CFG->limite) 
	{
		$errmsg[] = "El cupo del lugar no debe sobrepasar ".$CFG->limite;

	}
	elseif ($cupo < 5 ) {

		$errmsg[] = "El cupo del lugar no debe ser menor a 5.";
	}
  }
  // Si no hay errores verifica que el lugar no este ya dado de alta en la tabla
  if (empty($errmsg)) {

      $room = get_record('lugar', 'nombre_lug', $nombre_lugar);

      if (!empty($root) && $room->id != $idlugar) {
        	$errmsg[] = "El nombre del lugar que elegiste ya ha sido dado de alta; por favor elige otro.";
      }
  }

  // Si hubo error(es) muestra los errores que se acumularon.
  if (!empty($errmsg)) {
      showError($errmsg);
  }
// Si todo esta bien vamos a darlo de alta
else { // Todas las validaciones Ok 
 	 // vamos a darlo de alta

	if (empty($cupo))
	{
		$cupo=99999;
	}

    $room = new StdClass;
    $room->id = $idlugar;
    $room->nombre_lug = $nombre_lugar;
    $room->ubicacion = $ubicacion;
    $room->cupo = $cupo;

    if (!update_record('lugar', $room)) {
    }
?>

<p>Lugar para evento actualizado,</p>
<p>Si tienes preguntas o no sirve adecuadamente la pagina, por favor contacta a <a href="mailto:<?=$CFG->adminmail ?>">Administración <?=$CFG->conference_name ?></a></p>

<?php
    $values = array(
        'Nombre' => $nombre_lugar,
        'Ubicación' => $ubicacion,
        'Cupo' => $cupo
        );
    do_table_values($values);
?>

<p id="buttons">
    <input type="button" value="Volver al Listado" onClick="location.href='<?=$CFG->wwwroot ?>/admin/admin.php?opc=5'" />
</p>

<?php
    do_footer(); 
	exit;
    }
    //END
}
// Solo deja de imprimirse cuando todos los valores han sido introducidos correctamente
// de lo contrario la imprimira para poder introducir los datos si es que todavia no hemos introducido nada
// o para corregir datos que ya hayamos tratado de introducir
else {
    $room = get_record('lugar', 'id', $idlugar);

    if (!empty($room)) {
        $nombre_lugar = $room->nombre_lug;
        $ubicacion = $room->ubicacion;
        $cupo = $room->cupo;
    }
}
?>

<form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
    <p><i>Campos marcados con un asterisco son obligatorios</i></p>
	<table>

	<tr>
	<td class="name">Nombre: * </td>
    <td class="input"><input type="text" name="S_nombre_lug" size="50" value="<?=$nombre_lugar ?>"></td>
	<td></td>
	</tr>
		
    <tr>
    <td class="name">Ubicación: * </td>
    <td class="input"><input type="text" name="S_ubicacion" size="50" value="<?=$ubicacion ?>"></td>
    <td></td>
    </tr>

<?php
		if ($cupo!=99999) {
?>
	<tr>
		<td class="name">Cupo: </td>
		<td class="input">
			<select name="I_cupo">

            <option name="unset" value="" <?=(empty($cupo)) ? 'selected="selected"' : '' ?>></option>
<?php
			for ($Icupo=$CFG->limite;$Icupo>=5;$Icupo--) {
                $Ncupo = sprintf('%02d', $Icupo);
?>
            <option value="<?=$Ncupo ?>" <?=($Ncupo == $cupo) ? 'selected="selected"': '' ?>><?=$Ncupo ?></option>
<?php
            }
?>
            </select>
        </td>
        <td></td>
    </tr>
<?php
		}
?>
</table>

<p id="buttons">
		<input type="submit" name="submit" value="Actualizar">
        <input type="button" value="Cancelar" onClick="location.href='<?=$CFG->wwwroot ?>/admin/admin.php?opc=5'" />
</p>

</form>

<?php
    do_footer(); 
?>
