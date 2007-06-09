<?php
    // list admins
    require_once('header-common.php');

    $idadmin=(int) $_SESSION['YACOMASVARS']['rootid'];

    // list of admin users
    $users = get_records_sql('SELECT adm.*, t.descr as tdescr 
    FROM administrador adm
    LEFT JOIN tadmin t 
    ON adm.id_tadmin = t.id
    WHERE adm.id NOT IN (?,?)', array($idadmin, 1));

    // list of admin levels/types
    $admin_levels = get_records('tadmin');
?>

<h1>Listado de administradores</h1>

<table border=0 align=center width=100%>
    <tr class="table-headers">
        <td>Login</td>
        <td>Nombre</td>
        <td>Apellidos</td>
        <td>Correo</td>
	    <td>Tipo admin</td>
	    <td>&nbsp;</td>
	</tr>

<?php

if (!empty($users)) {
    $trclass = 'even';

    foreach ($users as $user) {
?>

<tr class="<?=($trclass=='even') ? 'even' : 'odd' ?>">
    <td><?=$user->login ?></td>
    <td><?=$user->nombrep ?></td>
    <td><?=$user->apellidos ?></td>
    <td><?=$user->mail ?></td>
    <td>
<?php
    foreach ($admin_levels as $level) {
        if ($level->id != $user->id_tadmin) {
?>

    <a class="verde" href="act_admin.php?admin=<?=$user->id ?>&level=<?=$level->id ?>&return_path=<?=$_SERVER['REQUEST_URI'] ?>"><?=$level->descr ?></a> |

<?php
        } else {
?>
    <strong><?=$level->descr ?></strong> |
<?php
        }
    }
?>

    </td>
    <td><a class="precaucion" href="Badmin.php?admin=<?=$user->id ?>">Eliminar</a></td>
</tr>

<?php
        // Toggle class even<->odd
        $trclass = ($trclass=='even') ? 'odd' : 'even';
    }
}
?>

</table>

<div id="buttons">
    <input type="button" value="Volver al menu" onClick="location.href='<?=$CFG->wwwroot ?>/admin/menuadmin.php'" />
</div>

<?php
do_footer();
?>
