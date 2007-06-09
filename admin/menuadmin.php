<?php
require_once('../includes/lib.php');

global $CFG;

beginSession('R');
do_header();

$idadmin=$_SESSION['YACOMASVARS']['rootid'];

$admin = get_record_select('administrador', 'id = ?', array($idadmin), 'login, nombrep, apellidos, id_tadmin as level');

if (!$admin) {
    die('Fatal: admin not found in database');
}

?>

<div id="login-info">
<p class="yacomas_login">Login: <?=$admin->login ?> | <a class="precaucion" href="signout.php">Desconectarse</a></p>
</div>

<div id="menuadmin-welcome" class="welcome center">
    <h2>Bienvenido Administrador</h2>
    <h3><?=$admin->nombrep ?> <?=$admin->apellidos ?></h3>

</div>

<div id="menuadmin">

    <div id="menuadmin-admin" class="menuadmin column">
        <a name="admin"></a>
        <h3>Administración</h3>
        <ul>
<?php if ($admin->level == 1) { ?>
            <li><a href="admin.php?opc=config">Configuración</a></li>
            <li><a href="admin.php?opc=add">Agregar administrador</a></li>
            <li><a href="admin.php?opc=list">Listar administradores</a></li>
            <li><a href="admin.php?opc=papers/deleted">Listar ponencias eliminadas</a></li>
<?php } ?>
            <li><a href="admin.php?opc=edit">Modificar mis datos</a></li>
        </ul>
    </div>

<?php if ($admin->level < 3) { ?>
    <div id="menuadmin-lugares" class="menuadmin column">
        <a name="lugares"></a>
        <h3>Lugares y fechas</h3>
        <ul>
            <li><a href="admin.php?opc=rooms/add">Registrar lugar</a></li>
            <li><a href="admin.php?opc=dates/register">Registrar fecha</a></li>
            <li><a href="admin.php?opc=rooms/list">Listado de lugares</a></li>
            <li><a href="admin.php?opc=dates/list">Listado de fechas</a></li>
        </ul>
    </div>
<?php } ?>

    <div id="menuadmin-ponencias" class="menuadmin column">
        <a name="ponencias"></a>
        <h3>Ponencias y ponentes</h3>
        <ul>
<?php if ($admin->level < 3) { ?>
            <li><a href="admin.php?opc=speakers/add">Agregar ponente</a></li>
            <li><a href="admin.php?opc=papers/add">Agregar ponencia</a></li>
<?php } ?>
            <li><a href="admin.php?opc=speakers/list">Listado de ponentes</a></li>
            <li><a href="admin.php?opc=papers/list">Listado de ponencias</a></li>
        </ul>
    </div>

    <div id="menuadmin-eventos" class="menuadmin column">
        <a name="eventos"></a>
        <h3>Eventos y asistentes</h3>
        <ul>
<?php if ($admin->level < 3) { ?>
            <li><a href="admin.php?opc=events/add">Registro de evento</a></li>
<?php } ?>
            <li><a href="admin.php?opc=events/list">Listado de eventos</a></li>
<?php if ($admin->level < 3) { ?>
            <li><a href="admin.php?opc=workshop/addperson">Inscripción asistente a talleres/tutoriales</a></li>
            <li><a href="admin.php?opc=workshop/removeperson">Baja asistentes a talleres/tutoriales</a></li>
            <li><a href="admin.php?opc=persons/list">Listado de asistentes</a></li>
<?php } ?>
            <li><a href="admin.php?opc=persons/control">Control de asistencias</a></li>
        </ul>
    </div>

</div><!-- #menuadmin -->

<div class="clear"></div>

<?php
    do_footer();
?>
