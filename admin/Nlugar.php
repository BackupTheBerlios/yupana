<?php
    require_once('header-common.php');

    $submit = optional_param('submit');
    $nombre_lugar = strtoupper(optional_param('S_nombre_lug'));
    $ubicacion = optional_param('S_ubicacion');
    $cupo = optional_param('I_cupo', 0, PARAM_INT);
?>

<h1>Registro de Lugares para ponencias</h1>    

<?php
if ($submit == "Registrar") {

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

	} elseif ($cupo < 5) {

		$errmsg[] = "El cupo del lugar no debe ser menor a 5";
	}
  }

  // Si no hay errores verifica que el lugar no este ya dado de alta en la tabla
  if (empty($errmsg)) {

      if (record_exists('lugar', 'nombre_lug', $nombre_lugar)) {
          $errmsg[] = 'El nombre del lugar que elegiste ya ha sido dado de alta; por favor elgite otro.';
      }

  }

  // Si hubo error(es) muestra los errores que se acumularon.
  if (!empty($errmsg)) {
      showError($errmsg);
  }

// Si todo esta bien vamos a darlo de alta
else { // Todas las validaciones Ok 
 	 // vamos a darlo de alta
    
    $room = new StdClass;
    $room->nombre_lug = $nombre_lugar;
    $room->ubicacion = $ubicacion;

	if (!empty($cupo)) 
	{
        $room->cupo = $cupo;
	}

    if ($rs = insert_record('lugar', $room)) {
?>
<p>Lugar para evento agregado, ahora ya podra asignarlo a cualquier propuesta aceptada.</p>
<p>Si tienes preguntas o no sirve adecuadamente la pagina, por favor contacta a <a href="mailto:<?=$CFG->adminmail ?>">Administración <?=$CFG->conference_name ?></a></p>

<?php
        $values = array(
            'Nombre' => $nombre_lugar,
            'Ubicación' => $ubicacion,
            'Cupo' => $cupo,
            );

        do_table_values($values);
    } else {
?>

<p class="error center">No se pudo insertar los datos.</p>

<?php
    }
?>

<p id="buttons">
        <input type="button" value="Volver al Menu" onClick="location.href='<?=$CFG->wwwroot ?>/admin/menuadmin.php#lugares'" />
</p>

<?php
 	do_footer(); 
	exit;
    }
    //END
}
?>

<form method="POST" action="<?=$_SERVER['REQUEST_URI'] ?>">
    <p class="notice center">Campos marcados con un asterisco son obligatorios</p>

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
		
		<tr>
		<td class="name">Cupo: </td>
		<td class="input">
            <select name="I_cupo">

            <option name="unset" value="" <?=(empty($cupo)) ? 'selected="selected"' : '' ?>></option>

<?php
		for ($Icupo=$CFG->limite;$Icupo>=5;$Icupo--){
			$Ncupo = sprintf("%02d", $Icupo);
?>
            <option value="<?=$Ncupo ?>" <?=($Ncupo == $cupo) ? 'selected="selected"' : '' ?>><?=$Ncupo ?></option>
<?php
		}
?>
		    </select>
		</td>

		<td>Dejar vacío si es lugar para conferencias</td>

		</tr>
		</table>

    <p id="buttons">
		<input type="submit" name="submit" value="Registrar" />
        <input type="button" value="Cancelar" onClick="location.href='<?=$CFG->wwwroot ?>/admin/menuadmin.php#lugares'" />
    </p>

    </form>

<?php
    do_footer(); 
?>
