<?php
    require_once('../includes/lib.php');

    $submit = optional_param('submit');
    $login = strtolower(optional_param('S_login'));
    $passwd = optional_param('S_passwd');
    $exp = optional_param('e');

    // Check if use has session
    session_start();
    if (!empty($_SESSION['YACOMASVARS']['asiid']) && $exp != 'exp') {
        header('Location: menuasistente.php');
    }

    $errmsg = array();

// para poder autorizar la insercion del registro
if (!empty($submit) && $submit == 'Iniciar') {
    if (empty($passwd) || !preg_match("/^\w{4,15}$/",$login)) {
  	    $errmsg[] = "Usuario y/o password no validos. Por favor trate de nuevo.";
    } else {

      $user = get_record('asistente', 'login', $login);

        if (!empty($user) || $user->passwd != md5($passwd)) {
        	$errmsg[] =  '<span class="err">Usuario y/o password incorrectos. Por favor intente de nuevo o <a href="reset.php"><br/>Presiona aqui para resetear tu password</a>.</span>';
	    } else {  # We have a winner!
	        # begin session
            @session_start(); //ignore errors
            session_register("YACOMASVARS");
            $_SESSION['YACOMASVARS']['asilogin'] = $user->login;
            $_SESSION['YACOMASVARS']['asiid'] = $user->id;
            $_SESSION['YACOMASVARS']['asilast'] = time();
            # re-route user
            header('Location: menuasistente.php');
            exit;
	     }
//	}
    } 
}

do_header();

// Aqui imprimimos la forma
// Solo deja de imprimirse cuando todos los valores han sido introducidos correctamente
// de lo contrario la imprimira para poder introducir los datos si es que todavia no hemos introducido nada
// o para corregir datos que ya hayamos tratado de introducir
?>

<h1>Inicio de Sesión Asistente</h1>

<?php

if (!empty($errmsg)) {
        showError($errmsg);
}

elseif ($exp == "exp") { 
    print '<span class="err">Su session ha caducado o no inicio session correctamente.  Por favor trate de nuevo.</span><p>'; 
}

?>

    <form method="POST" action="<?=$_SERVER['PHP_SELF'] ?>">

		<table>
            <tr>
            <td class="name">Nombre de Usuario: </td>
            <td class="input"><input type="text" name="S_login" size="15" value="<?=$login ?>"></td>
            </tr>

            <tr>
            <td class="name">Contraseña: </td>
            <td class="input"><input type="password" name="S_passwd" size="15" value=""></td>
            </tr>
		</table>
        

        <p id="buttons">
		    <input type="submit" name="submit" value="Iniciar">
		    <input type="button" value="Cancelar" onClick="location.href='../'">

        </p>
		</form>

<?php
do_footer();
?>
