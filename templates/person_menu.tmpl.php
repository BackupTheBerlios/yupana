<h1>Asistentes</h1>

<h2>Bienvenido <?=$USER->nombrep ?> <?=$USER->apellidos ?></h2>

<div id="menuadmin">

    <div class="menuadmin column">

        <ul>

            <li><a href="<?=get_url('person/details') ?>">Modificar mis datos</a></li> 
            <li><a href="<?=get_url('person/kardex') ?>">Hoja de registro</a></li>

        </ul>

    </div>

    <div class="menuadmin column">

        <ul>

            <li><a href="<?=get_url('person/events') ?>">Listas eventos programados</a></li>
            <li><a href="<?=get_url('person/workshops') ?>">Registro a talleres y/o tutoriales</a></li>

        </ul>

    </div>

</div>
