<?php
    // list admins
    require_once('header-common.php');

    $idadmin = (int) $_SESSION['YACOMASVARS']['rootid'];

    // list of admin users
    $query = 'SELECT adm.*, t.descr as tdescr 
            FROM administrador adm
            LEFT JOIN tadmin t 
            ON adm.id_tadmin = t.id
            WHERE adm.id NOT IN (?,?)';

    $users = get_records_sql($query, array($idadmin, 1));

?>

<h1>Listado de administradores</h1>

<?php
if (!empty($users)) {
    $admin_levels = get_records('tadmin');

    $table_data = array();
    $table_data[] = array(
        'Login',
        'Nombre',
        'Apellidos',
        'Correo',
        'Tipo Admin',
        'Acción'
    );

    foreach ($users as $user) {
        $tipo_admin = '';

        foreach ($admin_levels as $level) {

            if ($level->id == $user->id_tadmin) {

                $tipo_admin .= <<< END
<strong>{$level->descr}</strong> |
END;
            } else {

                $tipo_admin .= <<< END
<a class="verde" href="act_admin.php?admin={$user->id}&level={$level->id}&return_path={$_SERVER['REQUEST_URI']}">{$level->descr}</a> |
END;
            }
        }

        $action = <<< END
<a class="precaucion" href="Badmin.php?admin={$user->id}">Eliminar</a>
END;

        $table_data[] = array(
            $user->login,
            $user->nombrep,
            $user->apellidos,
            $user->mail,
            $tipo_admin,
            $action
        );
    }

    do_table($table_data, 'wide');
?>


<?php } else { ?>

<p class="error center">No se encontro ningún usuario administrador.</p>

<?php } ?>

<p id="buttons">
    <input type="button" value="Volver al menu" onClick="location.href='<?=$CFG->wwwroot ?>/admin/menuadmin.php'" />
</p>

<?php
do_footer();
?>
