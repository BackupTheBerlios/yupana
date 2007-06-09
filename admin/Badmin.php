<?php 
	require_once("header-common.php");
    global $CFG;

    $admin = optional_param('admin', 0, PARAM_INT);
    $submit = optional_param('submit');
?>

<h1>Eliminar Administrador</h1>

<?php
	$link=conectaBD();

// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if (!empty($submit) && $submit == 'Eliminar') {

    # do some basic error checking
    // Si todo esta bien vamos a borrar el registro 
    //if (delete_records('administrador', 'id', $admin)) {
    //}
    // can't delete the main admin
    if ($admin == 1) {
?>

    <div class="center">
        <p>No puede eliminar al administrador principal.</p>
        <input type="button" value="Volver a listado" onClick="location.href='<?=$CFG->wwwroot ?>/admin/admin.php?opc=1'" />
    </div>

<?php
    } else {

        $query1 = "DELETE FROM administrador WHERE id="."'".$_GET['admin']."'";
        $query2= "UPDATE propuesta SET id_administrador=1 WHERE id="."'".$_GET['admin']."'";
        $query3= "UPDATE evento SET id_administrador=1 WHERE id="."'".$_GET['admin']."'";
            //
            $result1 = mysql_query($query1) or err("No se puede eliminar los datos".mysql_errno($result1));
            $result2 = mysql_query($query2) or err("No se puede eliminar los datos".mysql_errno($result2));
            $result3 = mysql_query($query3) or err("No se puede eliminar los datos".mysql_errno($result3));
        print '	El administrador ha sido eliminado.<br>
            <p class="yacomas_msg">Las propuestas que ha autorizado el mismo han sido asignadas al administrador principal</p>
            <p>
             Si tienes preguntas o no sirve adecuadamente la pagina, por favor contacta a 
             <a href="mailto:'.$CFG->adminmail.'">Administraci&oacute;n '.$CFG->conference_name.'</a><br><br>
             <center>
             <input type="button" value="Volver a listado" onClick=location.href="'.$CFG->wwwroot.'/admin/admin.php?opc=1">
             </center>';

    }
}

// Aqui imprimimos la forma
else {
    $user = get_record('administrador', 'id', $admin);

    if (!empty($user)) {
        $values = array(
            'Login Administrador' => $user->login,
            'Nombre Administrador' => $user->nombrep,
            'Apellidos' => $user->apellidos,
            'Correo electrónico' => $user->mail
        );

        do_table_values($values);
?>

    <div id="buttons">
        <form method="POST" action="">
            <input type="submit" name="submit" value="Eliminar" />
            <input type="button" value="Cancelar" onClick="location.href='<?=$CFG->wwwroot ?>/admin/admin.php?opc=1'" />
            <input type="hidden" name="admin" value="<?=$admin ?>" />
        </form>
    </div>

<?php
    } else {
?>
    <p class="center">El usuario no existe.</p>
    <div id="buttons">
        <input type="button" value="Volver a listado" onClick="location.href='<?=$CFG->wwwroot ?>/admin/admin.php?opc=1'" />
    </div>
<?php  
    }
}

do_footer(); 
?>
