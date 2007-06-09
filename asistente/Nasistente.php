<?php
    require_once("../includes/lib.php");

    global $CFG;

    do_header();

    $submit = optional_param('submit');
    $login = strtolower(optional_param('S_login'));
    $passwd = optional_param('S_passwd');
    $passwd2 = optional_param('S_passwd2');
    $nombrep = optional_param('S_nombrep');
    $apellidos = optional_param('S_apellidos');
    $mail = optional_param('S_mail');
    $sexo = optional_param('C_sexo');
    $org = optional_param('S_org');
    $id_estudios = optional_param('I_id_estudios', 0, PARAM_INT);
    $ciudad = optional_param('S_ciudad');
    $id_estado = optional_param('I_id_estado', 0, PARAM_INT);
    $id_tasistente = optional_param('I_id_tasistente', 0, PARAM_INT);
    $b_day = optional_param('I_b_day', 0, PARAM_INT);
    $b_month = optional_param('I_b_month', 0, PARAM_INT);
    $b_year = optional_param('I_b_year', 0, PARAM_INT);

    // check values of sex
    $sexo = ($sexo == 'M' || $sexo == 'F') ? $sexo : '';

?>
<h1>Registro de Asistentes</h1>
<?php
    // Check if register of asistantants is closed 
    $status = get_field('config', 'status', 'id', REGASISTENTES);
    if (!$status) {
?>
    <div class="center">
        <p class="yacomas_error">El registro de asistentes se encuentra cerrado.</p>
        <p id="buttons"><input type="button" value="Continuar" onClick="location.href='../'" /></p>
    </div>
<?php
        do_footer();
        exit();
	}

// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if ($submit && $submit == "Registrarme") {
    # do some basic error checking
    $errmsg = array();

    // Verificar si todos los campos obligatorios no estan vacios
    if (empty($login)
        || empty($nombrep)
        || empty($apellidos)
        || empty($sexo)
        || empty($id_estudios)
        || empty($id_tasistente)
        || empty($id_estado)) { 

        $errmsg[] = "Verifica que los datos obligatorios los hayas introducido correctamente.";
    }

    if (!preg_match("/.+\@.+\..+/",$mail)) {     		
        $errmsg[] = "El correo electrónico no es válido";
    }

    // Verifica que el login sea de al menos 4 caracteres
    if (!preg_match("/^\w{4,15}$/",$login)) {
        $errmsg[] = "El login que elijas debe tener entre 4 y 15 caracteres.";
    }

    // Verifica que el password sea de al menos 6 caracteres
    if (!preg_match("/^.{6,15}$/",$passwd)) {
        $errmsg[] = "El password debe tener entre 6 y 15 caracteres.";
    }

    // Verifica que el password usado no sea igual al login introducido por seguridad
    elseif ($passwd == $login) {
        $errmsg[] = "El password no debe ser igual a tu login.";
    }

    // Verifica que los password esten escritos correctamente para verificar que
    // la persona introducjo correcamente el password que eligio.
    if ($passwd != $passwd2) {
        $errmsg[] = "Los passwords no concuerdan.";
    }

    // Si no hay errores verifica que el login no este ya dado de alta en la tabla
    if (empty($errmsg)) {
        if (record_exists('asistente', 'login', $login)) {
            $errmsg[] = 'El usuario que elegiste ya ha sido tomado; por favor elige otro';
        }
    }

    // Si hubo error(es) muestra los errores que se acumularon.
    if (!empty($errmsg)) {
        showError($errmsg);
    }    	

    // Si todo esta bien vamos a darlo de alta
    else {  // Todas las validaciones Ok 
            // vamos a darlo de alta

        $f_nac=$b_year.'-'.$b_month.'-'.$b_day;
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
        $user->fecha_nac = $f_nac;
        $user->reg_time = $date;
        $user->id_estudios = $id_estudios;
        $user->id_tasistente = $id_tasistente;
        $user->id_estado = $id_estado;

        // insert record
        if (!insert_record('asistente', $user)) {
            err("No se pudo insertar los datos.");
        }

	/////////////////////
	// Envia el correo:
	/////////////////////

    // Build url
    if (substr($CFG->wwwroot,0,4) == 'http') {
        $url = $CFG->wwwroot . '/asistente/';
    } else {
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $CFG->wwwroot . '/asistente/';
    }

?>
 <p class="center">Gracias por darte de alta, ahora ya podras accesar a tu cuenta.</p>
<?php

	$headers["From"]    = $CFG->general_mail;
	$headers["To"]      = $user->mail;
	$headers["Subject"] = "Registro de asistente";
	$message  = "";
	$message .= "Te has registrado como asistente al {$CFG->conference_name}\n";
	$message .= "Usuario: {$user->login}\n";
	$message .= "Contrase&ntilde;a: {$user->passwd}\n\n";
	$message .= "Puedes inicar sesion en: {$url}\n\n\n";
	$message .= "---------------------------------------\n";
	$message .= "{$CFG->conference_link}\n";
	$params["host"] = $CFG->smtp;
	$params["port"] = "25";
	$params["auth"] = false;
    // Added a verification to check if SEND_MAIL constant is enable patux@patux.net
    // TODO:
    // We need to wrap a function in include/lib.php to send emails in a generic way
    // This function must validate if SEND_MAIL is enable or disable
    if (SEND_MAIL == 1) // If is enable we will send the mail
    {
	    // Create the mail object using the Mail::factory method
	    //$mail_object =& Mail::factory("smtp", $params);
	    //$mail_object->send($user->mail, $headers, $message);
?>

<p class="center">Los datos de tu usuario y password han sido enviados al correo que registraste.</p>
<p class="center">Es posible que algunos servidores de correo registren el correo como correo no deseado  o spam y no se encuentre en su carpeta INBOX.</p>

<?php
    } else {
?>

<p class="center">Por razones de seguridad deshabilitamos el envío de correo.</p>

<?php

    }
?>

<p class="center">Si tienes preguntas o no sirve adecuadamente la página, por favor contacta a 
<a href="mailto:<?=$CFG->adminmail ?>">Administración <?=$CFG->conference_name ?></a></p>

<?php
    // Show values
    $values = array(
        'Nombre de Usuario' => $user->login,
        'Nombre(s)' => $user->nombrep,
        'Apellidos' => $user->apellidos,
        'Correo electrónico' => $user->mail,
        'Sexo' => ($user->sexo == 'M') ? 'Masculino' : 'Femenino',
        'Organización' => $user->org,
        'Estudios' => get_field('estudios', 'descr', 'id', $user->id_estudios),
        'Tipo de Asistente' => get_field('tasistente', 'descr', 'id', $user->id_tasistente),
        'Ciudad' => $user->ciudad,
        'Departamento' => get_field('estado', 'descr', 'id', $user->id_estado),
        'Fecha de Nacimiento' => $user->fecha_nac
    );

    // show table with values
    do_table_values($values);
?>

    <p id="buttons">
        <input type="button" value="Continuar" onClick="location.href='../'" />
    </p>

<?php
 	do_footer(); 
//	Necesitamos este exit para salirse ya de este programa y evitar que se imprima la forma porque 
//	los datos ya fueron intruducidos y la transaccion se realizo con exito
	exit;
    }
}

// Aqui imprimimos la forma
// Solo deja de imprimirse cuando todos los valores han sido introducidos correctamente
// de lo contrario la imprimira para poder introducir los datos si es que todavia no hemos introducido nada
// o para corregir datos que ya hayamos tratado de introducir
?>

    <form method="POST" action="<?=$_SERVER['REQUEST_URI'] ?>">
        <p class="yacomas_error">Asegurate de escribir bien tus datos personales ya que estos ser&aacute;n tomados para tu constancia de participaci&oacute;n</p>
        <p class="center"><i>Campos marcados con un asterisco son obligatorios</i></p>

		<table>

		<tr>
		<td class="name">Nombre de Usuario: * </td>
		<td class="input"><input type="text" name="S_login" size="15" value="<?=$login ?>"></td>
		<td>4 a 15 caracteres</td>
		</tr>

		<tr>
		<td class="name">Contraseña: * </td>
		<td class="input"><input type="password" name="S_passwd" size="15" value=""></td>
		<td>6 a 15 caracteres</td>
		</tr>

		<tr>
		<td class="name">Confirmación de Contraseña: * </td>
		<td class="input"><input type="password" name="S_passwd2" size="15" value=""></td>
		<td></td>
		</tr>

		<tr>
		<td class="name">Nombre(s): * </td>
        <td class="input"><input type="text" name="S_nombrep" size="30"	value="<?=$nombrep ?>"></td>
		<td></td>
		</tr>

		<tr>
		<td class="name">Apellidos: * </td>
        <td class="input"><input type="text" name="S_apellidos" size="30" value="<?=$apellidos ?>"></td>
		<td></td>
		</tr>

		<tr>
		<td class="name">Correo Electrónico: *</td>
        <td class="input"><input type="text" name="S_mail" size="15" value="<?=$mail ?>"></td>
		<td></td>
		</tr>

		<tr>
		<td class="name">Sexo: * </td>
        <td class="input">
            <select name="C_sexo">
            <option name="unset" value="" <?=(empty($sexo)) ? 'selected="selected"' : '' ?>></option>
            <option value="M" <?=($sexo == 'M') ? 'selected="selected"' : '' ?>>Masculino</option>
            <option value="F" <?=($sexo == 'F') ? 'selected="selected"' : '' ?>>Femenino</option>
		    </select>
		</td>
		<td></td>
		</tr>

		<tr>
		<td class="name">Organización: </td>
        <td class="input"><input type="text" name="S_org" size="15" value="<?=$org ?>"></td>
		<td></td>
		</tr>

		<tr>
		<td class="name">Estudios: * </td>
		<td class="input">
    		<select name="I_id_estudios">
            <option name="unset" value="0" <?=(empty($id_estudios)) ? 'selected="selected"' : '' ?>></option>

<?php
    $options = get_records('estudios');

    if (!empty($options)) {
        foreach ($options as $stud) {
?>
            <option value="<?=$stud->id ?>" <?=($id_estudios == $stud->id) ? 'selected="selected"' : '' ?>><?=$stud->descr ?></option>
<?php
        }
    }
?>
		    </select>
		</td>
		<td></td>
		</tr>

		<tr>
		<td class="name">Tipo de Asistente: *</td>
		<td class="input">
		    <select name="I_id_tasistente">
            <option name="unset" value="0" <?=(empty($id_tasistente)) ? 'selected="selected"' : '' ?>></option>
<?php
    $options = get_records('tasistente');
	
    if (!empty($options)) {
        foreach ($options as $t) {
?>
            <option value="<?=$t->id ?>" <?=($id_tasistente == $t->id) ? 'selected="selected"' : '' ?>><?=$t->descr ?></option>
<?php
        }

    }
?>
            </select>
        </td>
		<td></td>
		</tr>

		<tr>
		<td class="name">Ciudad: </td>
        <td class="input"><input type="text" name="S_ciudad" size="10" value="<?=$ciudad ?>"></td>
        <td></td>
		</tr>

		<tr>
		<td class="name">Departamento: * </td>
		<td class="input">
		    <select name="I_id_estado">
            <option name="unset" value="0" <?=(empty($id_estado)) ? 'selected="selected"' : '' ?>></option>

<?php
    $options = get_records('estado');
	
    if (!empty($options)) {
        foreach ($options as $state) {
?>
            <option value="<?=$state->id ?>" <?=($id_estado == $state->id) ? 'selected="selected"' : '' ?>><?=$state->descr ?></option>
<?php
        }

    }
?>
            </select>
        </td>
		<td></td>
		</tr>

		<tr>
		<td class="name">Fecha de Nacimiento: </td>
        <td class="input">
        Dia: 
    		<select name="I_b_day">
            <option name="unset" value="0" <?=(empty($b_day)) ? 'selected="selected"' : '' ?>></option>
<?php
		for ($Idia=1;$Idia<=31;$Idia++){
            $item = sprintf("%02d", $Idia);
?>
            <option value="<?=$item ?>" <?=($b_day == $Idia) ? 'selected="selected"' : '' ?>><?=$item ?></option>
<?php
		}
?>
		    </select>
		Mes:
		    <select name="I_b_month">
            <option name="unset" value="0" <?=(empty($b_month)) ? 'selected="selected"' : '' ?>></option>
<?php
		for ($Imes=1;$Imes<=12;$Imes++){
            $item = sprintf("%02d", $Imes);
?>
            <option value="<?=$item ?>" <?=($b_month == $Imes) ? 'selected="selected"' : '' ?>><?=$item ?></option>
<?php
		}
?>
		    </select>
        Año:
    		<select name="I_b_year">
            <option name="unset" value="0" <?=(empty($b_year)) ? 'selected="selected"' : '' ?>></option>
<?php
		for ($Iyear=1999;$Iyear>=1950;$Iyear--){
?>
            <option value="<?=$Iyear ?>" <?=($b_year == $Iyear) ? 'selected="selected"' : '' ?>><?=$Iyear ?></option>
<?php
		}
?>

             </select>
        </td>
		<td></td>
		</tr>

		</table>

		<p id="buttons">
		    <input type="submit" name="submit" value="Registrarme" />
		    <input type="button" value="Cancelar" onClick="location.href='../'" />
		</p>

		</form>

<?php

do_footer(); 

?>
