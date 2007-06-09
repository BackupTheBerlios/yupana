<?php
require_once('header-common.php');

$query = 'SELECT P.id, P.login, P.nombrep, P.apellidos,
        P.reg_time,  E.descr AS estado, ES.descr AS estudios 
		FROM ponente AS P, estado AS E, estudios AS ES 
		WHERE P.id_estado=E.id AND P.id_estudios=ES.id 
		ORDER BY P.id,P.reg_time';

$users = get_records_sql($query);
$total_ponentes = count_records('ponente');

?>

<h1>Listado de ponentes registrados</h1>
<h5><?=$total_ponentes ?> Ponentes Registrados</h5>

<table class="wide">
    <tr class="table-headers">
        <td>Nombre</td>
        <td>Login</td>
        <td>Estado</td>
        <td>Estudios</td>
        <td>Registro</td>

<?php
	if ($_SESSION['YACOMASVARS']['rootlevel']==1) {
?>
        <td></td>
<?php
    }
?>
    </tr>
<?php
    if (!empty($users)) {
        $trclass = 'even';

        foreach ($users as $user) {
?>
    <tr class="<?=($trclass == 'even') ? 'even' : 'odd' ?>">
        <td><a class="azul" href="Vponente.php?vopc=<?=$user->id ?> <?=$_SERVER['REQUEST_URI'] ?>"><?=$user->nombrep ?> <?=$user->apellidos ?></a></td>
        <td><?=$user->login ?></td>
        <td><?=$user->estado ?></td>
        <td><?=$user->estudios ?></td>
        <td><?=$user->reg_time ?></td>
<?php
        if ($_SESSION['YACOMASVARS']['rootlevel'] == 1) {
?>
        <td><a class="precaucion" href="Bponente.php?idponente=<?=$user->id ?>">Eliminar</a></td>
<?php
        }
?>
    </tr>
<?php
            // toggle trclass
            $trclass = ($trclass == 'even') ? 'odd' : 'even';
        }
    }
?>
</table>

<p id="buttons">
    <input type="button" value="Volver al menu" onClick="location.href='<?=$CFG->wwwroot ?>/admin/menuadmin.php#ponencias'" />
</p>

<?php
    do_footer();
?>
