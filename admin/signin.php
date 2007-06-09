<?php	
    require_once('../includes/lib.php');

    global $CFG;
    global $db;


    $submit = optional_param('submit');
    $login = optional_param('S_login');
    $pass = optional_param('S_passwd');
    $exp = optional_param('e');


    $errmsg = "";

    // check if has already session then redirect to main admin menu
    session_start();
    if (!empty($_SESSION['YACOMASVARS']['rootid']) && $exp != 'exp') {
        header('Location: menuadmin.php');
        exit;
    }

    // para poder autorizar la insercion del registro
    if ($submit == "Iniciar") {
        if (!preg_match("/^\w{4,15}$/",$login) || empty($pass)) {

            $errmsg .= 'Usuario y/o password no válidos. Por favor trate de nuevo.';

        } else {

            $admin = get_record_select('administrador', 'login=' . $db->qstr($login) . ' AND passwd=' . $db->qstr(md5($pass)));

            if (!empty($admin)) {
                # We have a winner!
                # begin session
//                session_start();
                session_register("YACOMASVARS");
                $_SESSION['YACOMASVARS']['rootlogin'] = $admin->login;
                $_SESSION['YACOMASVARS']['rootid'] = $admin->id;
                $_SESSION['YACOMASVARS']['rootlevel'] = $admin->id_tadmin;
                $_SESSION['YACOMASVARS']['rootlast'] = time();
                # re-route user
                header('Location: menuadmin.php');
                exit;
            } else {
                $errmsg .= 'Usuario y/o password no válidos. Por favor trate de nuevo.';
            }
        }
    }

    // Aqui imprimimos la forma
    // Solo deja de imprimirse cuando todos los valores han sido introducidos correctamente
    // de lo contrario la imprimira para poder introducir los datos si es que todavia no hemos introducido nada
    // o para corregir datos que ya hayamos tratado de introducir
    do_header();
?>

<h1>Módulo de administración</h1>
<h2 class="center">Inicio de Sesión</h2>

<?php

    if (!empty($errmsg)) {
?>
    <p class="error center"><?=$errmsg ?></p>

<?php } elseif ($exp == "exp") { ?>

<p class="error center">Su session ha caducado o no inicio session correctamente.  Por favor trate de nuevo.<p>

<?php } ?>

<form method="POST" action="">
    <div id="login-form" class="center">
        <p>
        <label for="S_login">Administrador: </label>
        <input type="text" name="S_login" size="15" value="<?=$login ?>" />
        </p>

        <p>
        <label for="S_passwd">Contraseña: </label>
        <input type="password" name="S_passwd" size="15" value="" />
        </p>

        <div id="buttons"> 
            <input type="submit" name="submit" value="Iniciar">
            <input type="button" value="Cancelar" onClick="location.href='../'">
        </div>
    </div>
</form>

<p class="notice center">Las Cookies deben ser habilitadas pasado este punto.<br/>Su sesión caducará despues de 1 hora de inactividad.</p>

<?php
do_footer();
?>
