<? 
    require_once('header-common.php');

    $submit = optional_param('submit');
    $day = optional_param('I_e_day', 0, PARAM_INT);
    $month = optional_param('I_e_month', 0, PARAM_INT);
    $year = optional_param('I_e_year', 0, PARAM_INT);
    $description = optional_param('S_descr');
?>

<h1>Registro de fechas para el Congreso</h1>

<?php
if ($submit == "Registrar") {
    # do some basic error checking
    $errmsg = array();

    // Verificar si todos los campos obligatorios no estan vacios
    if (empty($day) || empty($month) || empty($year)) {

        $errmsg[] = "Verifica que los datos obligatorios los hayas introducido correctamente.";

  }

    // Si no hay errores verifica que la fecha no este ya dado de alta en la tabla
    $fecha = $year.'-'.$month.'-'.$day;

    if (empty($errmsg)) {
        if (record_exists('fecha_evento', 'fecha', $fecha)) {
            $errmsg[] = "La fecha que elegiste ya sido dado de alta; por favor elige otra.";
        }
    }

    // Si hubo error(es) muestra los errores que se acumularon.
    if (!empty($errmsg)) {
        showError($errmsg);
    } else { // Todas las validaciones Ok 
             // vamos a darlo de alta
        $fecha_evento = new StdClass;
        $fecha_evento->fecha = $fecha;
        $fecha_evento->descr = $description;

        if (!$rs = insert_record('fecha_evento', $fecha_evento)) {
?>

<p class="error center">Nose pudo insertar los datos. Por favor contacta al administrador.</p>

<?php   } else { ?>

<p>Fecha para evento agregado, ahora ya podra asignarlo a cualquier propuesta aceptada.</p>
<p>Si tienes preguntas o no sirve adecuadamente la pagina, por favor contacta a <a href="mailto:<?=$CFG->adminmail ?>">Administración <?=$CFG->conference_name ?></a></p>

<?php
            $values = array(
                'Fecha evento' => $fecha,
                'Descripción' => $description
            );

            do_table_values($values);
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

$startyear=strftime("%Y");
?>

<form method="POST" action="<?=$_SERVER['REQUEST_URI'] ?>">
    <p class="notice center">Campos marcados con un asterisco son obligatorios. En caso de que el congreso tenga descripciones especiales para cada dia entonces llenar el campo descripción</p>

    <table class="narrow">
        <tr>
        <td class="name">Fecha evento*:</td>
        <td class="input">
        Año:<select name="I_e_year">
            <option name="unset" value="0" <?=(empty($year)) ? 'selected="selected"' : '' ?>></option>
<?php
    for ($Ianio=$startyear;$Ianio<=$startyear+1;$Ianio++) {
        $Nyear = sprintf("%02d", $Ianio);
?>
            <option value="<?=$Nyear ?>" <?=($year == $Ianio) ? 'selected="selected"' : '' ?>><?=$Nyear ?></option>

<?php } ?>
            </select>

        Mes:<select name="I_e_month">
            <option name="unset" value="0" <?=(empty($month)) ? 'selected="selected"' : '' ?>></option>
<?php
    for ($Imes=1;$Imes<=12;$Imes++) {
        $Nmonth = sprintf("%02d", $Imes);
?>
            <option value="<?=$Nmonth ?>" <?=($month == $Imes) ? 'selected="selected"' : '' ?>><?=month2name($Nmonth) ?></option>

<?php } ?>
            </select>

        Dia:<select name="I_e_day">
            <option name="unset" value="0" <?=(empty($day)) ? 'selected="selected"' : '' ?>></option>
<?php 
    for ($Idia=1;$Idia<=31;$Idia++) {
        $Nday = sprintf("%02d", $Idia);
?>
            <option value="<?=$Nday ?>" <?=($day == $Idia) ? 'selected="selected"' : '' ?>><?=$Nday ?></option>

<?php } ?>
            </select>

        </td>
        <td></td>
        </tr>

        <tr>
        <td class="name">Descripción: </td>
        <td class="input"><input type="text" name="S_descr" size="50" value="<?=$description ?>"></td>
        <td></td>
        </tr>
    </table>

    <p id="buttons">
        <input type="submit" name="submit" value="Registrar">
        <input type="button" value="Cancelar" onClick="location.href='<?=$CFG->wwwroot ?>/admin/menuadmin.php#lugares'" />
    </p>

</form>

<?php
do_footer();
?>
