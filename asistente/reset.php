<?php
    require_once("../includes/lib.php");
	do_header();

    $submit = optional_param('submit');
    $input = strtolower(optional_param('S_input'));
?>

<h1>Resetear contraseña</h1>

<?php

// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if ($submit == "Enviar") {
    # do some basic error checking
    $errmsg = array();

    // test for email 
    if (empty($input)) {
        $errmsg[] = 'Por favor ingrese su nombre de usuario o contraseña.';
    } elseif (preg_match("/.+\@.+\..+/",$input)) {     		
        // try get user from mail
        $user = get_record('asistente', 'mail', $input);
    } else {
        // try get user from login
        $user = get_record('asistente', 'login', $input);
    }

    if (!empty($errmsg)) {
      showError($errmsg);
    }
    // Si todo esta bien vamos a actualizar el contrasenia y a mandar el correo 
    else 	{ // Todas las validaciones Ok 

        // send password request if user exists
        if (!empty($user)) {
            request_password($user->login, 'asistente');
        }
?>
    <p>Se ha enviado un correo electrónico con las instrucciones para cambiar tu contraseña.</p>
	<p>Es posible que algunos servidores de correo registren el correo como correo no deseado  o spam y no se encuentre en su carpeta INBOX</p>

    <p id="buttons">
		<input type="button" value="Continuar" onClick=location.href="../">
    </p>
<?php
 	do_footer(); 
	exit;
  }
}
// Aqui imprimimos la forma
// Solo deja de imprimirse cuando todos los valores han sido introducidos correctamente
// de lo contrario la imprimira para poder introducir los datos si es que todavia no hemos introducido nada
?>
    <form method="POST" action="<?=$_SERVER['PHP_SELF'] ?>">
	<table>
    <tr>
		<td class="name">Ingrese su login o email:</td>
        <td class="input"><input type="text" name="S_input" size="30" value="<?=$input ?>"></td>
	</tr>
    </table>

    <p id="buttons">
		<input type="submit" name="submit" value="Enviar" />
		<input type="button" value="Cancelar" onClick="location.href='../'" />
    </p>

<?php
	do_footer();
?>
