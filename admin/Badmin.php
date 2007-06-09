<?php 
	require_once("header-common.php");

    $admin = optional_param('admin', 0, PARAM_INT);
    $submit = optional_param('submit');
?>

<h1>Eliminar Administrador</h1>

<?php
// Si la forma ya ha sido enviada checamos cada uno de los valores
// para poder autorizar la insercion del registro
if (!empty($submit) && $submit == 'Eliminar') {

    // can't delete the main admin
    if ($admin == 1) {
?>

<p class="error center">No puede eliminar al administrador principal.</p>

<?php
    } else {
        // check if user exists
        $admin_backup = get_record('administrador', 'id', $admin);

        if (!empty($admin_backup)) {
            // delete if user exists
            delete_records('administrador', 'id', $admin);
            // TODO: check above result
            $deleted = true;
        } else {
            $deleted = false; 
        }

        if ($deleted) {
            // propuestas modified by deleted admin asign to main admin
            execute_sql("UPDATE propuesta SET id_administrador=1 WHERE id=$admin", false);
            // evento modified by deleted admin asign to main admin
            execute_sql("UPDATE evento SET id_administrador=1 WHERE id=$admin", false);
?>

<p>El administrador ha sido eliminado. Las propuestas que ha autorizado el mismo han sido asignadas al administrador principal.</p>
<p>Si tienes preguntas o no sirve adecuadamente la pagina, por favor contacta a <a href="mailto:<?=$CFG->adminmail ?>">Administración <?=$CFG->conference_name ?></a></p>

<?php } else { ?>
            
<p class="error center">No se pudo eleminar los datos. Por favor contacte a su administrador.</p>

<?php
        }
    }
} else { // Show the user info to confirm delte
    $user = get_record('administrador', 'id', $admin);

    if (!empty($user)) {
        $values = array(
            'Login Administrador' => $user->login,
            'Nombre Administrador' => $user->nombrep,
            'Apellidos' => $user->apellidos,
            'Correo electrónico' => $user->mail
        );

        do_table_values($values);

        $no_back_button = true;
?>

<form method="POST" action="<?=$_SERVER['REQUEST_URI'] ?>">
    <p id="buttons">
        <input type="submit" name="submit" value="Eliminar" />
        <input type="button" value="Cancelar" onClick="location.href='<?=$CFG->wwwroot ?>/admin/admin.php?opc=1'" />
        <input type="hidden" name="admin" value="<?=$admin ?>" />
    </p>
</form>

<?php } else { ?>

<p class="error center">El usuario no existe.</p>

<?php  
    }
}

if (empty($no_back_button)) {
?>

<p id="buttons">
    <input type="button" value="Volver a listado" onClick="location.href='<?=$CFG->wwwroot ?>/admin/admin.php?opc=1'" />
</p>

<?php
}

do_footer(); 
?>
