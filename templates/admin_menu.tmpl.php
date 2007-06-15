<h1>Bienvenido Administrador</h1>

<h2><?=$USER->nombrep ?> <?=$USER->apellidos ?></h2>

<div id="menuadmin">

    <div id="menuadmin-ponencias" class="menuadmin column">

        <a name="ponencias"></a>

        <h3>Ponencias y ponentes</h3>

        <ul>

<?php if ($USER->id_tadmin < 3) { ?>

<li><a href="<?=get_url('admin/speakers/new') ?>">Agregar ponente</a></li>

<?php } ?>

<li><a href="<?=get_url('admin/speakers/') ?>">Listado de ponentes</a></li>

<?php if ($USER->id_tadmin < 3) { ?>

<li><a href="<?=get_url('admin/proposals/new') ?>">Agregar ponencia</a></li>

<?php } ?>


<li><a href="<?=get_url('admin/proposals/') ?>">Listado de ponencias</a></li>

        </ul>

    </div>

<?php if ($USER->id_tadmin < 3) { ?>

    <div id="menuadmin-lugares" class="menuadmin column">

        <a name="lugares"></a>

        <h3>Lugares y fechas</h3>

        <ul>

        <li><a href="<?=get_url('admin/rooms/new') ?>">Registrar lugar</a></li>
        <li><a href="<?=get_url('admin/rooms/') ?>">Listado de lugares</a></li>
        <li><a href="<?=get_url('admin/dates/new') ?>">Registrar fecha</a></li>
        <li><a href="<?=get_url('admin/dates/') ?>">Listado de fechas</a></li>

        </ul>

    </div>

    <div id="menuadmin-eventos" class="menuadmin column">

        <a name="eventos"></a>

        <h3>Eventos y asistentes</h3>

        <ul>

<?php if ($USER->id_tadmin < 3) { ?>

<li><a href="<?=get_url('admin/events/new') ?>">Registro de evento</a></li>

<?php } ?>

<li><a href="<?=get_url('admin/events') ?>">Listado de eventos</a></li>

<?php if ($USER->id_tadmin< 3) { ?>

<li><a href="<?=get_url('admin/workshops/add') ?>">Inscripción a talleres/tutoriales</a></li>
<li><a href="<?=get_url('admin/workshops/remove') ?>">Baja a talleres/tutoriales</a></li>
<li><a href="<?=get_url('admin/persons/') ?>">Listado de asistentes</a></li>

<?php } ?>

<li><a href="<?=get_url('admin/persons/control') ?>">Control de asistencias</a></li>

        </ul>

    </div>

    <div id="menuadmin-admin" class="menuadmin column">

        <a name="admin"></a>

        <h3>Administración</h3>

        <ul>

<?php if ($USER->id_tadmin == 1) { ?>

            <li><a href="<?=get_url('admin/config') ?>">Configuración</a></li>
            <li><a href="<?=get_url('admin/new') ?>">Agregar administrador</a></li>
            <li><a href="<?=get_url('admin/list') ?>">Listar administradores</a></li>
            <li><a href="<?=get_url('admin/proposals/deleted') ?>">Listar ponencias eliminadas</a></li>

<?php } ?>
            <li><a href="<?=get_url('admin/details') ?>">Modificar mis datos</a></li>

        </ul>

    </div>

<?php } ?>

</div><!-- #menuadmin -->
