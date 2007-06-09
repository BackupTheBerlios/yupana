<?php  
    // add new admin page
    require_once('header-common.php');

    $submit = optional_param('submit');
    $login = strtolower(optional_param('S_login'));
    $passwd = optional_param('S_passwd');
    $passwd2 = optional_param('S_passwd2');
    $nombrep = optional_param('S_nombrep');
    $apellidos = optional_param('S_apellidos');
    $mail = optional_param('S_mail');
    $tadmin = optional_param('I_id_tadmin', 0, PARAM_INT);

// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if (!empty($submit) && $submit == "Registrar") {
    # do some basic error checking
    $errmsg = array();

    // Verificar si todos los campos obligatorios no estan vacios
    if (empty($login)
        || empty($nombrep)
        || empty($apellidos)
        || empty($tadmin)) {

        $errmsg[] = "Verifica que los datos obligatorios los hayas introducido correctamente.";
    }

    // check email
    if (!preg_match("/.+\@.+\..+/",$mail)) {     		
        $errmsg[] = "El correo electrónico no es válido.";
    }

    // Verifica que el login sea de al menos 4 caracteres
    if (!preg_match("/^\w{4,15}$/",$_POST['S_login'])) {
        $errmsg[] = "El login que elijas debe tener entre 4 y 15 caracteres.";
    }
    
    // Verifica que el password sea de al menos 6 caracteres
    if (!preg_match("/^.{6,15}$/",$_POST['S_passwd'])) {
        $errmsg[] = "El password debe tener entre 6 y 15 caracteres.";
    }

    // Verifica que el password usado no sea igual al login introducido por seguridad
    if ($passwd == $login) {
        $errmsg[] = "El password no debe ser igual al login del administrador.";
    }

    // Verifica que los password esten escritos correctamente para verificar que
    // la persona introducjo correcamente el password que eligio.
    if ($passwd != $passwd2) {
        $errmsg[] = "Los passwords no concuerdan.";
    }

    // Si no hay errores verifica que el login no este ya dado de alta en la tabla
    
    if (empty($errmsg)) {
        if (record_exists('administrador', 'login', $login)) {
            $errmsg[] = 'El login que elegiste ya ha sido dado de alta; por favor elige etro.';
        }
    }

    // Si hubo error(es) muestra los errores que se acumularon.
    if (!empty($errmsg)) {
        showError($errmsg);
    } else { 
        // Si todo esta bien vamos a darlo de alta
        $newadmin = new stdClass();
        $newadmin->login = $login;
        $newadmin->passwd = md5($passwd);
        $newadmin->nombrep = $nombrep;
        $newadmin->apellidos = $apellidos;
        $newadmin->mail = $mail;
        $newadmin->id_tadmin = $tadmin;

        if (!insert_record('administrador', $newadmin)) {
?>  

<p class="error center">Ocurrió un error al insertar los datos. Por favor intenta de nuevo o contacta con el administrador.</p>

<?php   } else { ?>

<h3>Administrador agregado, ahora ya podrá utilizar la cuenta.</h3>
<p>Si tienes preguntas o no sirve adecuadamente la pagina, por favor contacta a <a href="mailto:<?=$CFG->adminmail ?>">Administración <?=$CFG->conference_name ?></a></p>

<?php
            $values = array(
                'Administrador Login' => $newadmin->login,
                'Nombre(s)' => $newadmin->nombrep,
                'Apellidos' => $newadmin->apellidos,
                'Correo Electrónico' => $newadmin->mail,
                'Tipo de administrador' => get_field('tadmin', 'descr', 'id', $newadmin->id_tadmin)
            );

            do_table_values($values);
?>

<p id="buttons">
    <input type="button" value="Volver al Menu" onClick="location.href='<?=$CFG->wwwroot ?>/admin/menuadmin.php#admin'" />
</p>

<?php
        }

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

    <p class="notice center">Campos marcados con un asterisco son obligatorios</p>

    <table>
        <tr>
            <td class="name">Administrador Login: * </td>
            <td class="input"><input TYPE="text" name="S_login" size="15" value="<?=$login ?>" /></td>
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
            <td class="name">Correo Electrónico: *</td>
            <td class="input"><input type="text" name="S_mail" size="15" value="<?=$mail ?>" /></td>
            <td></td>
        </tr>

        <tr>
            <td class="name">Tipo de administrador: * </td>
            <td class="input">
                <select name="I_id_tadmin">
                <option name="unset" value="0" <?=(!empty($tadmin)) ? 'selected="selected"' : ''?> ></option>
<?php 

$options = get_records('tadmin');
foreach($options as $option) { 

?>
                <option value="<?=$option->id ?>" <?=($option->id == $tadmin) ? 'selected="selected"' : '' ?>><?=$option->descr ?></option>
<?php } ?>
                </select>
            </td>
        </tr>
    </table>

    <p id="buttons">
        <input type="submit" name="submit" value="Registrar" />
        <input type="button" value="Cancelar" onClick="location.href='<?=$CFG->wwwroot ?>/admin/menuadmin.php#admin'" />
    </p>

</form>

<?php
do_footer(); 
?>
