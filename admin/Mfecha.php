<?php
    require_once('header-common.php');

    $submit = optional_param('submit');
    $idfecha = optional_param('idfecha', 0, PARAM_INT);
    $day = optional_param('I_e_day', 0, PARAM_INT);
    $month = optional_param('I_e_month', 0, PARAM_INT);
    $year = optional_param('I_e_year', 0, PARAM_INT);
    $description = optional_param('S_descr');
?>

<h1>Modificar fechas para congreso</h1>

<?php
// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if ($submit == "Actualizar") {
    # do some basic error checking
    $errmsg = array();

    // Verificar si todos los campos obligatorios no estan vacios
    if (empty($day) || empty($month) || empty($year)) {

        $errmsg[] = "Verifica que los datos obligatorios los hayas introducido correctamente.";
    }
    
    // Si no hay errores verifica que el lugar no este ya dado de alta en la tabla
//    $f_evento=$_POST['I_e_year'].'-'.$_POST['I_e_month'].'-'.$_POST['I_e_day'];
    $fecha = $year.'-'.$month.'-'.$day;

    if (empty($errmsg)) {
        $fecha_evento = get_record('fecha_evento', 'fecha', $fecha);

        if (!empty($fecha_evento) && $fecha_evento->id != $idfecha) {
            $errmsg[] = "La fecha que elegiste ya ha sido dada de alta; por favor elige otra.";
        }
    }

    // Si hubo error(es) muestra los errores que se acumularon.
    if (!empty($errmsg)) {
        showError($errmsg);
    } else { // Todas las validaciones Ok 
             // vamos a actualizarlo 
        $fecha_evento = new StdClass;
        $fecha_evento->id = $idfecha;
        $fecha_evento->fecha = $fecha;
        $fecha_evento->descr = $description;

        if (!$rs = update_record('fecha_evento', $fecha_evento)) {
?>

<p class="error center">Ocurrió un error al actualizar los datos. Por favor contacta al administrador.</p>

<?php   } else { ?>
    
<p>Fecha para evento actualizada.<br />
Si tienes una pregunta o no sirve adecuadamente la página, por favor contacta a <a href="mailto:<?=$CFG->adminmail ?>">Administración <?=$CFG->conference_name ?></a></p>

<?php
            $values = array(
                'Fecha evento' => $fecha,
                'Descripción' => $description
            );

            do_table_values($values);
        }
?>

<p id="buttons">
    <input type="button" value="Volver" onClick="location.href='<?=$CFG->wwwroot ?>/admin/admin.php?opc=12'" />
</p>

<?php
        do_footer();
        exit;
    }
    //END

} 

$fecha_evento = get_record('fecha_evento', 'id', $idfecha);

if (!empty($fecha_evento)) {
    $year = substr($fecha_evento->fecha, 0, 4);
    $month = substr($fecha_evento->fecha, 5, 2);
    $day = substr($fecha_evento->fecha, 8, 2);
    $description = $fecha_evento->descr;

    $startyear = strftime("%Y");
?>

<form method="POST" action="<?=$_SERVER['REQUEST_URI'] ?>">
    <p class="notice center">Campos marcados con un asterisco son obligatorios. En caso de que el congreso tenga descripciones especiales para cada dia entonces llenar el campo descripción</p>

    <table class="narrow">
        <tr>
        <td class="name">Fecha evento*:</td>
        <td class="input">
        Año:<select name="I_e_year">
<?php
    for ($Ianio=$startyear;$Ianio<=$startyear+1;$Ianio++) {
        $Nyear = sprintf("%02d", $Ianio);
?>
            <option value="<?=$Nyear ?>" <?=($year == $Ianio) ? 'selected="selected"' : '' ?>><?=$Nyear ?></option>

<?php } ?>
            </select>

        Mes:<select name="I_e_month">
<?php
    for ($Imes=1;$Imes<=12;$Imes++) {
        $Nmonth = sprintf("%02d", $Imes);
?>
            <option value="<?=$Nmonth ?>" <?=($month == $Imes) ? 'selected="selected"' : '' ?>><?=$Nmonth ?></option>

<?php } ?>
            </select>

        Dia:<select name="I_e_day">
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


<?php } else { 
        $no_submit = true;
?>

<p class="error center">No se encontraron los registros para la fecha requerida.</p>

<?php } ?>

<p id="buttons">
<?php if (empty($no_submit)) { ?>
    <input type="submit" name="submit" value="Actualizar" />
<?php } ?>
    <input type="button" value="Cancelar" onClick="location.href='<?=$CFG->wwwroot ?>/admin/admin.php?opc=12'" />
</p>

<?php
do_footer(); 
?>
