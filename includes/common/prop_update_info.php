<?php
    if (empty($CFG) || (Context != 'admin'
        && Context != 'ponente')) {
        die; //exit
    }

//    $is_new = (empty($idponente)) ? true : false;

    // build object to update
//    $prop = new StdClass;

    // common values
    $prop->nombre = $nombreponencia;
    $prop->id_ponente = $idponente;
    $prop->id_nivel = $id_nivel;
    $prop->id_prop_tipo = $id_tipo;
    $prop->id_orientacion = $id_orientacion;
    $prop->duracion = $duracion;
    $prop->resumen = $resumen;
    $prop->reqtecnicos = $reqtecnicos;
    $prop->reqasistente = $reqasistente;

    // new prop?
    if ($submit == 'Registrar') {
        $prop->reg_time = date('%Y%m%d%H%M%S');
    } else {
//        $prop->id = get_field('propuesta', 'nombre', $prop->nombre)  
        $prop->id = $idponencia;
    }

    // new prop
    if (empty($prop->id)) {
        $rs = insert_record('propuesta', $prop);
    } else {
        //revert prop_tipo and duracion if status in rejected, > acepted
        $prop_status = get_field('propuesta', 'id_status', 'id', $prop->id);

        if ($prop_status == 3 || $prop_status > 4) {
            $prop->id_prop_tipo = get_field('propuesta', 'id_prop_tipo', 'id', $prop->id);
            $prop->duracion = get_field('propuesta', 'duracion', 'id', $prop->id);
        }

        //update record
        $rs = update_record('propuesta', $prop);
    }

    if (!$rs) {
        // Fatal error
        show_error('Error Fatal: No se puedo insertar/actualizar los datos.');
        die;
    }

    if (empty($prop->id)) {
?>

<p>Tu propuesta de ponencia ha sido registrada.</p>

<?php //include($CFG->incdir . 'common/new_user_send_mail.php'); ?>

<?php } else { ?>
        
<p>Tu propuesta de ponencia ha sido actualizada.</p>

<?php } ?>

<p>Si tienes preguntas o la página no funciona correctamente, por favor
contacta a <a href="mailto:<?=$CFG->adminmail ?>">Administración <?=$CFG->conference_name ?></a></p>

<?php
    // refresh proposal from db
    if (!empty($prop->id)) {
        $proposal = get_record('propuesta', 'id', $prop->id);
    } else {
        $proposal = get_record('propuesta', 'nombre', $prop->nombre, 'id_ponente', $prop->id_ponente);
    }

    $proposal->ponencia = $proposal->nombre;
    $proposal->nivel = get_field('prop_nivel', 'descr', 'id', $proposal->id_nivel);
    $proposal->tipo = get_field('prop_tipo', 'descr', 'id', $proposal->id_prop_tipo);
    $proposal->orientacion = get_field('orientacion', 'descr', 'id', $proposal->id_orientacion);
    $proposal->status = get_field('prop_status', 'descr', 'id', $proposal->id_status);

    include($CFG->incdir . 'common/prop_display_info.php');
?>
