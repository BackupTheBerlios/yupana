<div id="frontpage">
    <h1>Registro</h1>

    <p>Gracias por tu interés en <?=$CFG->conference_name ?></p>

    <h3><a href="<?=get_url('speaker/register') ?>">Registro de ponentes</a>
    - <a href="<?=get_url('speaker/login') ?>">Accede a tu cuenta </a></h3>

    <p>Es necesario tu registro, mediante el cual podrás enviar ponencias y estar informado del evento.</p>

    <h3><a href="<?=get_url('person/register') ?>">Registro de asistentes </a>
    - <a href="<?=get_url('person/login') ?>">Accede a tu cuenta </a></h3>

    <p>Es necesario tu registro, mediante el cual podrás realizar preinscripción a <?=$CFG->conference_name ?>
    y  talleres/tutoriales además de mantenerte informado del evento.</p>

<?php if (!empty($CFG->public_proposals)) { ?>

    <h3><a href="<?=get_url('general/proposals') ?>">Lista preliminar de ponencias</a></h3>

    <p>Aquí podrás ver las propuestas ponencias que han sido enviadas y el status en el que se encuentran dichas ponencias.</p>

<?php } ?>

<?php if (!empty($CFG->public_schedule)) { ?>

    <h3><a href="<?=get_url('general/schedule') ?>">Programa preliminar</a></h3>

    <p>Aquí podrás ver el programa preliminar de ponencias y eventos.</p>

<?php } ?>

    <h3><a href="<?=get_url('general/information') ?>">Modalidades de participacion</a></h3>

    <p>Modalidades de las ponencias que encontraras en el evento!</p>
</div>

<div class="block"></div>
