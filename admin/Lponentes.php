<?php
    require_once('header-common.php');

    $request_uri = $_SERVER['REQUEST_URI'];
    $rootlevel = $_SESSION['YACOMASVARS']['rootlevel'];
    $total_ponentes = count_records('ponente');
?>

<h1>Listado de ponentes registrados</h1>
<h3><?=$total_ponentes ?> Ponentes Registrados</h3>

<?php
$query = 'SELECT P.id, P.login, P.nombrep, P.apellidos,
    P.reg_time,  E.descr AS estado, ES.descr AS estudios
    FROM ponente AS P, estado AS E, estudios AS ES
    WHERE P.id_estado=E.id AND P.id_estudios=ES.id
    ORDER BY P.id,P.reg_time';

$users = get_records_sql($query);

if (!empty($users)) {
    $table_data = array();

    $table_headers = array('Nombre', 'Login', 'Departamento', 'Estudios', 'Registro');
    if ($rootlevel == 1) {
        $table_headers = array_merge($table_headers, array('Acción'));
    }

    $table_data[] = $table_headers;

    foreach ($users as $user) {
        $l_nombre = <<< END
<a class="azul" href="Vponente.php?vopc={$user->id} {$request_uri}">{$user->nombrep} {$user->apellidos}</a>
END;
        $row_data = array($l_nombre, $user->login, $user->estado, $user->estudios, $user->reg_time);

        if ($rootlevel == 1) {
            $l_action = <<< END
<a class="precaucion" href="Bponente.php?idponente={$user->id}">Eliminar</a>
END;
            $row_data = array_merge($row_data, array($l_action));
        }

        $table_data[] = $row_data;
    }

    do_table($table_data, 'wide');

} else {
?>

<p class="error center">No se tiene ningún ponente registrado.</p>

<?php } ?>

<p id="buttons">
    <input type="button" value="Volver al menu" onClick="location.href='<?=$CFG->wwwroot ?>/admin/menuadmin.php#ponencias'" />
</p>

<?php
    do_footer();
?>
