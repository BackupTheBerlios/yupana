<?php
// dummy check
if (empty($CFG) || $USER->id_tadmin != 1) {
    die;
}

// can edit current user or root admin
$where = 'ADM.id != 1 AND ADM.id !='. $USER->id;

//run prop filters
include($CFG->admdir . 'admin_filter_optional_params.php');

$users = get_admins($where);
?>

<h1>Lista de administradores</h1>

<?php
if (!empty($users)) {
?>

<h4>Administradores registrados: <?=sizeof($users) ?></h4>

<?php
    // show admin filter form
    include($CFG->admdir . 'admin_filter.php');

    // build data table
    $table_data = array();
    $table_data[] = array('Login', 'Nombre', 'Apellidos', 'Correo', 'Tipo admin', '');

    $tadmins = get_records('tadmin');

    foreach ($users as $user) {

        $login = '<ul><li>'.$user->login.'</li></ul>';
        $actions = '<ul class="list-vmenu">';

        $admin_url = get_url('admin');

        foreach ($tadmins as $tadmin) {
            if ($tadmin->id == $user->id_tadmin) {
                $actions .= <<< END
<li>{$tadmin->descr}</li>
END;
            } else {
                $action_url = $admin_url . '/' . $user->id . '/type/' . $tadmin->id;
                $actions .= <<< END
<li><a class="verde" title="{$tadmin->tareas}" href="{$action_url}">{$tadmin->descr}</a></li>
END;
            }
        }

        $actions .= '</ul>';

        $delete = <<< END
<a href="{$admin_url}/{$user->id}/delete" title="Eliminar">Eliminar</a>
END;

        $table_data[] = array(
            $login,
            $user->nombrep,
            $user->apellidos,
            $user->mail,
            $actions,
            $delete
            );
    }

    do_table($table_data, 'wide');

} else {
?>
<div class="block"></div>

<p class="error center">No se encontro ningún usuario.</p>

<?php 
}

do_submit_cancel('', 'Volver al Menu', get_url('admin'))
?>
