<h1>Bienvenido Administrador</h1>

<h2><?=$USER->nombrep ?> <?=$USER->apellidos ?></h2>

<div id="menuadmin">

    <div id="menuadmin-admin" class="menuadmin column">

        <a name="admin"></a>

        <h3>Administración</h3>

        <ul>

<?php if ($USER->id_tadmin == 1) { ?>

            <li><a href="admin.php?opc=config">Configuración</a></li>
            <li><a href="admin.php?opc=add">Agregar administrador</a></li>
            <li><a href="admin.php?opc=list">Listar administradores</a></li>
            <li><a href="admin.php?opc=papers/deleted">Listar ponencias eliminadas</a></li>

<?php } ?>
            <li><a href="admin.php?opc=edit">Modificar mis datos</a></li>

        </ul>

    </div>

<?php if ($USER->id_tadmin < 3) { ?>

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

<?php if ($USER->id_tadmin < 3) { ?>

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

<?php if ($USER->id_tadmin < 3) { ?>

            <li><a href="admin.php?opc=events/add">Registro de evento</a></li>

<?php } ?>

            <li><a href="admin.php?opc=events/list">Listado de eventos</a></li>

<?php if ($USER->id_tadmin< 3) { ?>

            <li><a href="admin.php?opc=workshop/addperson">Inscripción asistente a talleres/tutoriales</a></li>
            <li><a href="admin.php?opc=workshop/removeperson">Baja asistentes a talleres/tutoriales</a></li>
            <li><a href="admin.php?opc=persons/list">Listado de asistentes</a></li>

<?php } ?>

            <li><a href="admin.php?opc=persons/control">Control de asistencias</a></li>

        </ul>

    </div>

</div><!-- #menuadmin -->
