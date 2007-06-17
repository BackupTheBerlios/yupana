<?php
// dummy check
if (empty($CFG) || Context != 'admin') {
    die;
}

$where = '1=1';

// run filters
include($CFG->comdir . 'user_filter_optional_params.php');

if (Action == 'listspeakers') {
    $users = get_speakers($where);
    $desc = 'Ponentes';
    $local_url = 'speakers';
}

elseif (Action == 'listpersons') {
    $users = get_persons($where);
    $desc = 'Asistentes';
    $local_url = 'persons';
}
?>

<h1>Lista de <?=$desc ?></h1>

<?php
if (!empty($users)) {
?>

<h4><?=$desc ?> registrados: <?=sizeof($users) ?></h4>

<?php
    // show filter form
    include($CFG->comdir . 'user_filter.php');

    // build data table
    $table_data = array();
    $table_data[] = array('Nombre', 'Login', 'Departamento', 'Estudios', 'Registro', '');

    foreach ($users as $user) {

        $url = get_url('admin/'.$local_url.'/'.$user->id);
        $l_nombre = <<< END
<ul><li>
<a class="speaker" href="{$url}">{$user->apellidos} {$user->nombrep}</a>
</li></ul>
END;
        $url = get_url('admin/'.$local_url.'/'.$user->id.'/delete');
        $l_delete = <<< END
<a class="precaucion" href="{$url}">Eliminar</a>
END;
        
        $table_data[] = array(
            $l_nombre,
            $user->login,
            $user->estado,
            $user->estudios,
            $user->reg_time,
            $l_delete
            );
    }

    do_table($table_data, 'wide');

} else {
    $return_url = get_url('admin');
?>
<div class="block"></div>

<p class="error center">No se encontraron registros.</p>

<?php 
}
?>
