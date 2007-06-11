<?php
    require_once("../includes/lib.php");

    require('common/user_optional_params.php');

    do_header();
?>

<h1>Registro de Asistentes</h1>

<?php
    // Check if register of asistantants is closed 
    $status = get_field('config', 'status', 'id', REGASISTENTES);
    if (!$status) {
?>

<p class="error center">El registro de asistentes se encuentra cerrado.</p>

<p id="buttons">
    <input type="button" value="Continuar" onClick="location.href='../'" />
</p>

<?php
        do_footer();
        exit();
    }

// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if ($submit && $submit == "Registrarme") {
    # do some basic error checking
    $errmsg = array();

    require('common/user_optional_params_check.php');

    // Si hubo error(es) muestra los errores que se acumularon.
    if (!empty($errmsg)) {
        showError($errmsg);
    }       

    // Si todo esta bien vamos a darlo de alta
    else {  // Todas las validaciones Ok 
            // vamos a darlo de alta

        $date=strftime("%Y%m%d%H%M%S");

        // build user object
        $user = new StdClass;
        $user->login = $login;
        $user->passwd = md5($passwd);
        $user->nombrep = $nombrep;
        $user->apellidos = $apellidos;
        $user->sexo = $sexo;
        $user->mail = $mail;
        $user->ciudad = $ciudad;
        $user->org = $org;
        $user->fecha_nac = $fecha_nac;
        $user->reg_time = $date;
        $user->id_estudios = $id_estudios;
        $user->id_tasistente = $id_tasistente;
        $user->id_estado = $id_estado;

        // insert record
        if (!$rs = insert_record('asistente', $user)) {
            err("No se pudo insertar los datos.");
        }

?>

 <p class="center">Gracias por darte de alta, ahora ya podras accesar a tu cuenta.</p>

<?php require('common/nasistente_send_mail.php'); ?>

<p>Si tienes preguntas o no sirve adecuadamente la página, por favor contacta a 
<a href="mailto:<?=$CFG->adminmail ?>">Administración <?=$CFG->conference_name ?></a></p>

<?php require('common/nasistente_display_values.php'); ?>

    <p id="buttons">
        <input type="button" value="Continuar" onClick="location.href='<?=$CFG->wwwroot ?>/asistente/'" />
    </p>

<?php
    do_footer(); 
    exit;
    //END
    }
}

// show form
?>

    <form method="POST" action="<?=$_SERVER['REQUEST_URI'] ?>">
        <p class="error center">Asegúrate de escribir bien tus datos personales ya que estos serán tomados para tu constancia de participación</p>
        <p class="center"><i>Campos marcados con un asterisco son obligatorios</i></p>

<?php require('common/display_user_info_input_table.php'); ?>

        <p id="buttons">
            <input type="submit" name="submit" value="Registrarme" />
            <input type="button" value="Cancelar" onClick="location.href='../'" />
        </p>

    </form>

<?php
do_footer(); 
?>
