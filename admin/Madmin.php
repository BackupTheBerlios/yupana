<?php
    require_once('header-common.php');

    $submit = optional_param('submit');
    $login = strtolower(optional_param('S_login'));
    $passwd = optional_param('S_passwd');
    $passwd2 = optional_param('S_passwd2');
    $nombrep = optional_param('S_nombrep');
    $apellidos = optional_param('S_apellidos');
    $mail = optional_param('S_mail');
    $idadmin=$_SESSION['YACOMASVARS']['rootid'];
?>

<h1>Modificar datos de administrador</h1>

<?php
// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if ($submit == "Modificar") {
    # do some basic error checking
    $errmsg = array();

    // Verificar si todos los campos obligatorios no estan vacios
    if (empty($login)
        || empty($nombrep)
        || empty($apellidos)) {

        $errmsg[] = "Verifica que los datos obligatorios los hayas introducido correctamente.";
    }

    if (!preg_match("/.+\@.+\..+/",$mail)) {     		
        $errmsg[] = "El correo electrónico no es válido";
    }

    // Verifica que el login sea de al menos 4 caracteres
    if (!preg_match("/^\w{4,15}$/",$login)) {
        $errmsg[] = "El login que elijas debe tener entre 4 y 15 caracteres";
    }

    // Verifica que el password sea de al menos 6 caracteres
    if (!empty($passwd)) {
        if (!preg_match("/^.{6,15}$/",$passwd)) {
            $errmsg[] = "El password debe tener entre 6 y 15 caracteres";
        }

        // Verifica que el password usado no sea igual al login introducido por seguridad
        if ($passwd == $login) {
            $errmsg[] = "<li>El password no debe ser igual a tu login";
        }

        // Verifica que los password esten escritos correctamente para verificar que
        // la persona introducjo correcamente el password que eligio.
        if ($passwd != $passwd2) {
            $errmsg[] = "Los passwords no concuerdan";
        }
    }

    // Si no hay errores verifica que el login no este ya dado de alta en la tabla
    if (empty($errmsg)) {
        $user = get_record('administrador', 'login', $login);

        if (!empty($user) && $user->id != $idadmin) {
            $errmsg[] = "El login que elegiste ya ha sido dado de alta; por favor elige otro";
        }
    }

    // Si hubo error(es) muestra los errores que se acumularon.
    if (!empty($errmsg)) {
        showError($errmsg);
    } else {
        // Todas las validaciones Ok 
        // vamos a darlo de alta
        $admin = new StdClass;
        $admin->id = $idadmin;
        $admin->login = $login;
        $admin->nombrep = $nombrep;
        $admin->apellidos = $apellidos;
        $admin->mail = $mail;

        if (!empty($passwd)) {
            $admin->passwd = md5($passwd);
        }

        if (update_record('administrador', $admin)) {
?>

<p>Administrador modificado.</p>
<p>Si tienes preguntas o no sirve adecuadamente la pagina, por favor contacta a <a href="mailto:<?=$CFG->adminmail ?>">Administración <?=$CFG->conference_name ?></a></p>

<?php
            $values = array(
                'Administrador Login' => $admin->login,
                'Nombre(s)' => $admin->nombrep,
                'Apellidos' => $admin->apellidos,
                'Correo Electrónico' => $admin->mail
            );

            do_table_values($values);
        
        } else { 
?>

<p class="error center">Ocurrió un error al modificar los datos. Por favor contacta al administrador.</p>

<?php   } ?>

<p id="buttons">
    <input type="button" value="Volver al Menu" onClick="location.href='<?=$CFG->wwwroot ?>/admin/menuadmin.php#admin'" />
</p>

<?php

        do_footer();

        // Necesitamos este exit para salirse ya de este programa y evitar que se imprima la forma porque 
        // los datos ya fueron intruducidos y la transaccion se realizo con exito
        exit;
    }
} else {
    // set user data if exists
    $user = get_record('administrador', 'id', $idadmin);

    if (!empty($user)) {
        $login = $user->login;
        $nombrep = $user->nombrep;
        $apellidos = $user->apellidos;
        $mail = $user->mail;
    }
}
// show form 
?>

<form method="POST" action="<?=$_SERVER['REQUEST_URI'] ?>">
    <p class="notice center">Campos marcados con un asterisco son obligatorios<br />Deja los campos de contraseña vacios para manterner tu contraseña actual</p>

    <table>
        <tr>
        <td class="name">Administrador Login: * </td>
        <td class="input"><input type="text" name="S_login" size="15" value="<?=$login ?>" /></td>
        <td> 4 a 15 caracteres</td>
        </tr>

        <tr>
        <td class="name">Contraseña: * </td>
        <td class="input"><input type="password" name="S_passwd" size="15" value="" /></td>
        <td> 6 a 15 caracteres</td>
        </tr>

        <tr>
        <td class="name">Confirmación de Contraseña: * </td>
        <td class="input"><input type="password" name="S_passwd2" size="15" value="" /></td>
        <td></td>
        </tr>

        <tr>
        <td class="name">Nombre(s): * </td>
        <td class="input"><input type="text" name="S_nombrep" size="30" value="<?=$nombrep ?>" /></td>
        <td></td>
        </tr>

        <tr>
        <td class="name">Apellidos: * </td>
        <td class="input"><input type="text" name="S_apellidos" size="30" value="<?=$apellidos ?>" /></td>
        <td></td>
        </tr>

        <tr>
        <td class="name">Correo electronico: * </td>
        <td class="input"><input type="text" name="S_mail" size="30" value="<?=$mail ?>" /></td>
        <td></td>
        </tr>
    </table>

    <p id="buttons">
        <input type="submit" name="submit" value="Modificar" />
        <input type="button" value="Cancelar" onClick="location.href='<?=$CFG->wwwroot ?>/admin/menuadmin.php#admin'" />
    </p>
</form>';

<?php
do_footer(); 
?>
