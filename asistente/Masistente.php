<?php
	require_once('header-common.php');

    require('common/user_optional_params.php');

	$idasistente=$_SESSION['YACOMASVARS']['asiid'];
?>

<h1>Modificar datos de asistente</h1>

<?php
// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if ($submit == 'Actualizar') {
    # do some basic error checking
    $errmsg = array();

    require('common/user_optional_params_check.php');

    // Si hubo error(es) muestra los errores que se acumularon.
    if (!empty($errmsg)) {
        showError($errmsg);
    } else {
        // Todas las validaciones Ok 
        // vamos a darlo de alta

        $user = new StdClass;
        $user->id = $idasistente;
        $user->login = $login;
        $user->nombrep = $nombrep;
        $user->apellidos = $apellidos;
        $user->mail = $mail;
        $user->sexo = $sexo;
        $user->org = $org;
        $user->id_estudios = $id_estudios;
        $user->id_tasistente = $id_tasistente;
        $user->ciudad = $ciudad;
        $user->id_estado = $id_estado;
        $user->fecha_nac = $fecha_nac;

        if (!empty($passwd)) {
            $user->passwd = md5($passwd);
        }

        if ($rs = update_record('asistente', $user)) {
?>

<p>Datos actualizados correctamente.</p>
<p>Si tienes preguntas o no sirve adecuadamente la página por favor contacta a <a href="mailto:<?=$CFG->adminmail ?>">Administración <?=$CFG->conference_name ?></a></p>

<?php
            $estudios = get_field('estudios', 'descr', 'id', $user->id_estudios);
            $tasistente = get_field('tasistente', 'descr', 'id', $user->id_tasistente);
            $estado = get_field('estado', 'descr', 'id', $user->id_estado);

            $values = array(
                'Nombre de Usuario' => $user->login,
                'Nombre(s)' => $user->nombrep,
                'Apellidos' => $user->apellidos,
                'Correo Electrónico' => $user->mail,
                'Sexo' => ($user->sexo == 'M') ? 'Masculino' : 'Femenino',
                'Organización' => $user->org,
                'Estudios' => $estudios,
                'Tipo de Asistente' => $tasistente,
                'Ciudad' => $user->ciudad,
                'Departamento' => $estado,
                'Fecha de Nacimiento' => $user->fecha_nac
                );

            do_table_values($values);
        } else {
?>

<p class="error center">Ocurrió un error al modificar los datos. Por favor contacta al administrador.</p>

<?php   } ?>

<p id="buttons">
    <input type="button" value="Volver al Menu" onClick="location.href='<?=$CFG->wwwroot ?>/asistente/menuasistente.php'" />
</p>

<?php
        do_footer();
        exit();
    }
} else {
    // set user data if exists
    $user = get_record('asistente', 'id', $idasistente);

    if (!empty($user)) {
        $login = $user->login;
        $nombrep = $user->nombrep;
        $apellidos = $user->apellidos;
        $mail = $user->mail;
        $sexo = $user->sexo;
        $org = $user->org;
        $id_estudios = $user->id_estudios;
        $id_tasistente = $user->id_tasistente;
        $ciudad = $user->ciudad;
        $id_estado = $user->id_estado;
        $fecha_nac = $user->fecha_nac;

        // split fecha_nac in day, month and year
        $b_date = split('-', $fecha_nac);
        $b_day = ($b_date[2]) ? (int) $b_date[2] : 0;
        $b_month = ($b_date[1]) ? (int) $b_date[1] : 0;
        $b_year = ($b_date[0]) ? (int) $b_date[0] : 0;
    }
}
// show form
?>

<form method="POST" action="<?=$_SERVER['REQUEST_URI'] ?>">
    <p class="notice center">Campos marcados con un asterisco son obligatorios</br />Deja los campos de contraseña vacios para mantener tu contraseña actual</p>

    <p class="notice center">Asegúrate de escribir bien tus datos personales ya que estos serían tomados para tu constancia de participación</p>

<?php require('common/display_user_info_input_table.php'); ?>

    <p id="buttons">
        <input type="submit" name="submit" value="Actualizar" />
        <input type="button" value="Volver al Menu" onClick="location.href='<?=$CFG->wwwroot ?>/asistente/menuasistente.php'" />
    </p>

</form>

<?php
do_footer();
?>
