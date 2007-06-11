<?php
    require_once("../includes/lib.php");

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
        if (!$rs = insert_record('asistente', $user)) {
            err("No se pudo insertar los datos.");
        }

?>

 <p class="center">Gracias por darte de alta, ahora ya podras accesar a tu cuenta.</p>

<?php
        if ($CFG->send_mail) {
            // Build url
            if (substr($CFG->wwwroot,0,4) == 'http') {
                $url = $CFG->wwwroot . '/asistente/';
            } else {
                $url = 'http://' . $_SERVER['SERVER_NAME'] . $CFG->wwwroot . '/asistente/';
            }

            $toname = $user->nombrep .' '. $user->apellidos;
            $to = $user->mail;
            $subject = $CFG->conference_name . ': Registro de asistente';
            $message = <<< END
Te has registrado como asistente al {$CFG->conference_name}

  Usuario:    {$user->login}
  Contraseña: {$passwd}

Puedes iniciar sesión entrando a la siguiente dirección:

  {$url}


--
Equipo {$CFG->conference_name}
{$url}

END;

            //3.. 2.. 1.. go!
            send_mail($toname, $to, $subject, $message);
?>

<p>Los datos de tu usuario y password han sido enviados al correo que registraste.</p>
<p>Es posible que algunos servidores de correo registren el correo como correo no deseado  o spam y no se encuentre en su carpeta INBOX.</p>

<?php
        } else {
?>

<p class="center">Por razones de seguridad deshabilitamos el envío de correo.</p>

<?php } ?>

<p>Si tienes preguntas o no sirve adecuadamente la página, por favor contacta a 
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
        'Fecha de Nacimiento' => sprintf('%s', $user->fecha_nac)
    );

    // show table with values
    do_table_values($values);
?>

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

<?php include('common/display_user_info_input_table.php'); ?>

        <p id="buttons">
            <input type="submit" name="submit" value="Registrarme" />
            <input type="button" value="Cancelar" onClick="location.href='../'" />
        </p>

    </form>

<?php
do_footer(); 
?>
