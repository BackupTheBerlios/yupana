<h1>Ponentes</h1>

<h2>Bienvenido <?=$USER->nombrep ?> <?=$USER->apellidos ?></h2>

<div id="menuadmin">

    <div class="menuponente column">

        <ul>
        <li><a href="<?=get_url('speaker/details') ?>">Modificar mis datos</a></li>
        <li><a href="<?=get_url('speaker/proposals/new') ?>">Enviar propuesta de ponencia</a></li>
        <li><a href="<?=get_url('speaker/proposals') ?>">Lista de propuestas enviadas</a></li>

<?php if (events_for('speaker', $USER->id)) { ?>

        <li><a href="<?=get_url('speaker/events') ?>">Lista de mis eventos programados</a></li>

<?php } ?>

        </ul>

    </div>

</div>
