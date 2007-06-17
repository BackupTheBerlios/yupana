<?php
// run setup script 
require_once(dirname(__FILE__).'/setup.php');
require($CFG->incdir . 'displaylib.php');
require($CFG->incdir . 'infolib.php');

function set_config($name, $value) {
    global $CFG;

    $CFG->$name = $value;
    
    if (get_field('datalists', 'name', 'name', $name)) {
        return set_field('datalists', 'value', $value, 'name', $name);
    } else {
        $config = new StdClass;
        $config->name = $name;
        $config->value = $value; 
        return insert_record('datalists', $config);
    }
}

function get_config($name=NULL) {
    global $CFG;
    
    if (!empty($name)) {
        return get_record('datalists', 'name', $name);
    }

    // this was originally in setup.php, duh!
    if ($configs = get_records('datalists')) {
        $localcfg = (array)$CFG;

        foreach ($configs as $config) {
            if (empty($localcfg[$config->name])) {
                $localcfg[$config->name] = $config->value;
            }
        }

        $localcfg = (object)$localcfg;
        return $localcfg;
    } else {
        // preserve $CFG if db returns nothing or error
        return $CFG;
    }
}

function get_url($path='') {
    global $CFG;

    $url = $CFG->wwwroot;

    if (!empty($path)) {
        // using mod rewrite?
        if (empty($CFG->clean_url)) {
            $url .= '/?q=' . $path;
        } else {
            $url .= '/' . $path;
        }
    }

    return $url;
}

function clean_text($text, $format=FORMAT_MOODLE) {

    global $ALLOWED_TAGS;

    switch ($format) {
        case FORMAT_PLAIN:
            return $text;

        default:

        /// Remove tags that are not allowed
//            $text = strip_tags($text, $ALLOWED_TAGS);
            $text = strip_tags($text);
            
        /// Add some breaks into long strings of &nbsp;
            $text = preg_replace('/((&nbsp;){10})&nbsp;/', '\\1 ', $text);

        /// Remove script events
            $text = eregi_replace("([^a-z])language([[:space:]]*)=", "\\1Xlanguage=", $text);
            $text = eregi_replace("([^a-z])on([a-z]+)([[:space:]]*)=", "\\1Xon\\2=", $text);

            return $text;
    }
}

// returns particular value for the named variable taken from
// POST or GET, otherwise returning a given default.

function optional_param ($varname, $default=NULL, $options=PARAM_CLEAN) {

    if (isset($_POST[$varname])) {  // POST has precedence
        $param = $_POST[$varname];
    } else if (isset($_GET[$varname])){
        $param = $_GET[$varname];
    } else {
        return $default;
    }

    return clean_param($param, $options);
}

// clean the variables and/or cast to specific types, based on
// an options field

function clean_param ($param, $options) {

    global $CFG;

    if (is_array($param)) {              // Let's loop
        $newparam = array();
        foreach ($param as $key => $value) {
            $newparam[$key] = clean_param($value, $options);
        }
        return $newparam;
    }

    if (!$options) {
        return $param;                   // Return raw value
    }

    //this corrupts data - Sven
    //if ((string)$param == (string)(int)$param) {  // It's just an integer
    //    return (int)$param;
    //}

    if ($options & PARAM_CLEAN) {
// this breaks backslashes in user input
//        $param = stripslashes($param);   // Needed by kses to work fine
        $param = clean_text($param);     // Sweep for scripts, etc
// and this unnecessarily escapes quotes, etc in user input
//        $param = addslashes($param);     // Restore original request parameter slashes
    }

    if ($options & PARAM_INT) {
        $param = (int)$param;            // Convert to integer
    }

    if ($options & PARAM_ALPHA) {        // Remove everything not a-z
        $param = eregi_replace('[^a-zA-Z]', '', $param);
    }

    if ($options & PARAM_ALPHANUM) {     // Remove everything not a-zA-Z0-9
        $param = eregi_replace('[^A-Za-z0-9]', '', $param);
    }

    if ($options & PARAM_ALPHAEXT) {     // Remove everything not a-zA-Z/_-
        $param = eregi_replace('[^a-zA-Z/_-]', '', $param);
    }

    if ($options & PARAM_BOOL) {         // Convert to 1 or 0
        $tempstr = strtolower($param);
        if ($tempstr == 'on') {
            $param = 1;
        } else if ($tempstr == 'off') {
            $param = 0;
        } else {
            $param = empty($param) ? 0 : 1;
        }
    }

    if ($options & PARAM_NOTAGS) {       // Strip all tags completely
        $param = strip_tags($param);
    }

    if ($options & PARAM_SAFEDIR) {     // Remove everything not a-zA-Z0-9_-
        $param = eregi_replace('[^a-zA-Z0-9_-]', '', $param);
    }

    if ($options & PARAM_FILE) {         // Strip all suspicious characters from filename
        $param = ereg_replace('[[:cntrl:]]|[<>"`\|\':\\/]', '', $param);
        $param = ereg_replace('\.\.+', '', $param);
        if($param == '.') {
            $param = '';
        }
    }

    if ($options & PARAM_PATH) {         // Strip all suspicious characters from file path
        $param = str_replace('\\\'', '\'', $param);
        $param = str_replace('\\"', '"', $param);
        $param = str_replace('\\', '/', $param);
        $param = ereg_replace('[[:cntrl:]]|[<>"`\|\':]', '', $param);
        $param = ereg_replace('\.\.+', '', $param);
        $param = ereg_replace('//+', '/', $param);
        $param = ereg_replace('/(\./)+', '/', $param);
    }

    if ($options & PARAM_HOST) {         // allow FQDN or IPv4 dotted quad
        preg_replace('/[^\.\d\w-]/','', $param ); // only allowed chars
        // match ipv4 dotted quad
        if (preg_match('/(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})/',$param, $match)){
            // confirm values are ok
            if ( $match[0] > 255
                 || $match[1] > 255
                 || $match[3] > 255
                 || $match[4] > 255 ) {
                // hmmm, what kind of dotted quad is this?
                $param = '';
            }
        } elseif ( preg_match('/^[\w\d\.-]+$/', $param) // dots, hyphens, numbers
                   && !preg_match('/^[\.-]/',  $param) // no leading dots/hyphens
                   && !preg_match('/[\.-]$/',  $param) // no trailing dots/hyphens
                   ) {
            // all is ok - $param is respected
        } else {
            // all is not ok...
            $param='';
        }
    }

    if ($options & PARAM_CLEANHTML) {
//        $param = stripslashes($param);         // Remove any slashes 
        $param = clean_text($param);           // Sweep for scripts, etc
//        $param = trim($param);                 // Sweep for scripts, etc
    }

    return $param;
}

// send mail
function send_mail($contactname, $contactemail, $subject, $message, $myname='', $mymail='', $bcc='', $replyto='', $replytoname='') {
    global $CFG;
    // needed for compatibility
    $CFG->libdir = dirname(__FILE__);

    // include mailer library
    include_once(dirname(__FILE__).'/phpmailer/class.phpmailer.php');

    $mail = new phpmailer;

    $mail->Version = 'Yacomas (Rho)';
    $mail->PluginDir = $CFG->libdir . '/phpmailer/';

    $mail->CharSet = 'UTF-8';

    if (empty($CFG->smtp)) {
        $mail->IsMail();
    } else {
        $mail->IsSMTP();

        $mail->Host = $CFG->smtp;

        if (!empty($CFG->smtpuser) && !empty($CFG->smtppass)) {
            $mail->SMTPAuth = true;
            $mail->Username = $CFG->smtpuser;
            $mail->Password = $CFG->smtppass;
        }
    }

    $mail->Sender = $CFG->adminmail;

    if (!empty($myname) && !empty($mymail)) {
        $mail->From = $mymail;
        $mail->FromName = $myname;
    } elseif (!empty($myname)) {
        $mail->From = $CFG->adminmail;
        $mail->FronNAme = $myname;
    } else {
        $mail->From = $CFG->general_mail;
        $mail->FromName = $CFG->conference_name;
    }

    if (!empty($replyto) && !empty($replytoname)) {
        $mail->AddReplyTo($replyto, $replytoname);
    }

    $mail->Subject = substr(stripslashes($subject),0,900);
    $mail->AddAddress($contactemail, $contactname);

    $mail->WordWrap = 79;

    $mail->IsHTML(false);
    $mail->Body = "\n$message\n";

    if ($CFG->send_mail == 1) {
        if ($mail->Send()) {
            return true;
        } else {
            print_object($mail->ErrorInfo);
            return false;
        }
    } 

    if ($CFG->debug > 7) {
        print_object($mail);
    }
}

function request_password($login, $type) {
    global $CFG;

    if ($type == 'A') {
        $user_type = 'person';
    } elseif ($type == 'P') {
        $user_type = 'speaker';
    } else {
        // duh!
        return false;
    }

    $user = get_record($table, 'login', $login);

    if (empty($user)) {
        return false;
    }

    $pwreq = new StdClass;
    $pwreq->user_id = $user->id;
    $pwreq->user_type = $table;
    $pwreq->code = 'req' . substr(base_convert(md5(time() . $user->login), 16, 24), 0, 30);

    if (!insert_record('password_requests', $pwreq)) {
        return false;
    } else {
       $subject = "{$CFG->conference_name}: Cambio de contraseña {$table}";

       $url = get_url('recover_password/'.$pwreq->code);

       $message = "";
       $message .= "Has solicitado un cambio de contraseña para el usuario {$user->login}\n";
       $message .= "Para confirmarlo ingrese a la siguiente dirección:\n";
       $message .= "  {$url}\n\n";
       $message .= "--";
       $message .= "{$CFG->conference_name}\n";
       $message .= "{$CFG->conference_link}\n";
    }

    return send_mail($user->namep.' '.$user->apellidos, $user->mail, $subject, $message);
}

function generatePassword() {

    $salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    srand((double)microtime()*1000000);  
    $i = 0;
    $pass = '';
    while ($i < 15) {  // change for other length
        $num = rand() % 33;
        $tmp = substr($salt, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }
    return $pass;
}

function strftime_caste($formato, $fecha){
// strftime por Marcos A. Botta
// $fromato: como se quiere mostrar la fecha
// $fecha: tiemestamp correspondiente a la fecha y hora que se quiere mostrar
$salida = strftime($formato,  $fecha);
	    // reemplazo meses  
	    $salida = ereg_replace("January","Enero",$salida);
	    $salida = ereg_replace("February","Febrero",$salida);
	    $salida = ereg_replace("March","Marzo",$salida);
	    $salida = ereg_replace("April","Abril",$salida);
	    $salida = ereg_replace("May","Mayo",$salida);
	    $salida = ereg_replace("June","Junio",$salida);
	    $salida = ereg_replace("July","Julio",$salida);
	    $salida = ereg_replace("August","Agosto",$salida);
	    $salida = ereg_replace("September","Septiembre",$salida);
	    $salida = ereg_replace("October","Octubre",$salida);
	    $salida = ereg_replace("November","Noviembre",$salida);
	    $salida = ereg_replace("December","Diciembre",$salida);
            // reemplazo meses cortos
	    $salida = ereg_replace("Jan","ene",$salida);
	    $salida = ereg_replace("Apr","abr",$salida);
	    $salida = ereg_replace("Aug","ago",$salida);
	    $salida = ereg_replace("Dec","dic",$salida);
	    // reemplazo di'as
	    $salida = ereg_replace("Monday","Lunes",$salida);
	    $salida = ereg_replace("Tuesday","Martes",$salida);
	    $salida = ereg_replace("Wednesday","Mi&eacute;rcoles",$salida);
	    $salida = ereg_replace("Thursday","Jueves",$salida);
	    $salida = ereg_replace("Friday","Viernes",$salida);
	    $salida = ereg_replace("Saturday","S&aacute;bado",$salida);
	    $salida = ereg_replace("Sunday","Domingo",$salida);
	    // reemplazo dias cortos
	    $salida = ereg_replace("Mon","Lun",$salida);
	    $salida = ereg_replace("Tue","Mar",$salida);
	    $salida = ereg_replace("Wed","Mie",$salida);
	    $salida = ereg_replace("Thu","Jue",$salida);
	    $salida = ereg_replace("Fri","Vie",$salida);
	    $salida = ereg_replace("Sat","Sab",$salida);
	    $salida = ereg_replace("Sun","Dom",$salida);
	    // reemplazo cuando es 1 de algun mes
	    $salida = ereg_replace(" 01 de "," 1&deg; de ",$salida);
	    return $salida;
	    } // fin strftime_caste
//--------------------------------
function err ($errmsg) {
  print "<p><span class=\"err\">Se han encontrado problemas : <i>$errmsg</i>.<p>Por favor conctacte al <a href=\"mailto:".$adminmail."?subject=Problema con Yacomas- $errmsg\">Administrador</a>.</span><p>";
  exit;
}

function month2name ($id) {
    $result = $id;
    $id = (int) $id;

    switch ($id) {
        case  1: $result = 'Enero'; break;
        case  2: $result = 'Febrero'; break;
        case  3: $result = 'Marzo'; break;
        case  4: $result = 'Abril'; break;
        case  5: $result = 'Mayo'; break;
        case  6: $result = 'Junio'; break;
        case  7: $result = 'Julio'; break;
        case  8: $result = 'Agosto'; break;
        case  9: $result = 'Septiembre'; break;
        case 10: $result = 'Octubre'; break;
        case 11: $result = 'Noviembre'; break;
        case 12: $result = 'Diciembre'; break;
    }

    return $result;
}

// DELETEME: BD conection moved to adodb
function conectaBD()
{
    global $CFG;

    if(!($link=mysql_pconnect($CFG->dbhost,$CFG->dbuser,$CFG->dbpass)))
    {
        print("No se puede hacer la conexion a la Base de Datos");
        die();
    } 
    mysql_select_db($CFG->dbname) or die (mysql_error());

}

function beginSession($tipo) {
    global $CFG;

    @session_start(); //ignore errors
	session_register("YACOMASVARS");
	switch ($tipo)
	{
		case 'P': 
			  $login='ponlogin';
			  $id='ponid';
			  $last='ponlast';
			  break;
		case 'A':
			  $login='asilogin';
			  $id='asiid';
			  $last='asilast';
			  break;
		case 'R':
			  $login='rootlogin';
			  $id='rootid';
			  $last='rootlast';
			  $level='rootlevel';
			  break;
	}

    // Check if $last index exists, if not set to 0
    if (!empty($_SESSION['YACOMASVARS'][$last])) {
        $last_time = $_SESSION['YACOMASVARS'][$last];
    } else {
        $last_time = 0; 
    }

	$t_transcurrido = time() - $last_time;
	$hora = 3600;

	if ($tipo == 'R')
	{
		if (empty($_SESSION['YACOMASVARS'][$login]) || empty($_SESSION['YACOMASVARS'][$id]) || 
		    empty($_SESSION['YACOMASVARS'][$level]) ||
	            ($t_transcurrido > $hora))

		{    # 1 hour exp.
            header('Location: ' . get_url('admin/login'));
			exit;
		}
	}
	else 
	{
		
		if (empty($_SESSION['YACOMASVARS'][$login]) || empty($_SESSION['YACOMASVARS'][$id]) || 
	            ($t_transcurrido > $hora))
		{    # 1 hour exp.
            header('Location: ' . get_url('logout'));
			exit;
		}
	}

	$_SESSION['YACOMASVARS'][$last] = time();
}

function verificaForm($id_tipo_usuario, $tabla){
		   // Verificar si todos los campos obligatorios no estan vacios
		  $errmsg="";
		  if (empty($_POST['S_login']) || empty($_POST['S_nombrep']) || empty($_POST['S_apellidos']) ||
		    	empty($_POST['C_sexo']) || empty($_POST['I_id_estudios']) || empty($_POST[$id_tipo_usuario]) || 
			empty($_POST['I_id_estado'])) { 
			$errmsg .= "<li>Verifica que los datos obligatorios los hayas introducido correctamente </li>";
		  }
		  if (!preg_match("/.+\@.+\..+/",$_POST['S_mail'])) {     		
		  	$errmsg .= "<li>El correo electronico tecleado no es valido";
		  }
		  // Verifica que el login sea de al menos 4 caracteres
		  if (!preg_match("/^\w{4,15}$/",$_POST['S_login'])) {
		        $errmsg .= "<li>El login que elijas debe tener entre 4 y 15 caracteres";
		  }
		  // Verifica que el password sea de al menos 6 caracteres
		  if (!preg_match("/^.{6,15}$/",$_POST['S_passwd'])) {
		        $errmsg .= "<li>El password debe tener entre 6 y 15 caracteres";
		  }
		  // Verifica que el password usado no sea igual al login introducido por seguridad
		  elseif ($_POST['S_passwd'] == $_POST['S_login']) {
		        $errmsg .= "<li>El password no debe ser igual a tu login";
		  }
		  // Verifica que los password esten escritos correctamente para verificar que
		  // la persona introducjo correcamente el password que eligio.
		  if ($_POST['S_passwd'] != $_POST['S_passwd2']) {
		        $errmsg .= "<li>Los passwords no concuerdan";
		  }
		  // Si no hay errores verifica que el login no este ya dado de alta en la tabla
		  if (empty($errmsg)) {
		      $lowlogin = strtolower($_POST['S_login']);
		      $userQuery = 'SELECT * FROM '.$tabla.' WHERE login="'.$lowlogin.'"';
		      $userRecords = mysql_query($userQuery) or err("No se pudo checar el login".mysql_errno($userRecords));
		      if (mysql_num_rows($userRecords) != 0) {
		        $errmsg .= "<li>El usuario que elegiste ya ha sido tomado; por favor elige otro";
		      }
		  }
		  return $errmsg;
		  // Si hubo error(es) muestra los errores que se acumularon.
}	

//initial gettext code
//compatibility code
//gettext workaround
if (!function_exists('__gettext')) {
    function __gettext($s) {
        return __($s);
    }

    if (!function_exists('__')) {
        function __($s) {
            return __($s)
        }
    }
}



?>
