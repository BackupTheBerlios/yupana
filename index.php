<?php
require_once('includes/lib.php');

$q= optional_param('q');
switch ($q) {
    case 'proposals/info':
        do_header('Modalidades de partipación');
        require($CFG->rootdir . 'template/proposals_info.tmpl.php');
        break;

    default: // default index
        do_header('Registro');
?>

<div id="frontpage">
    <h1>Registro</h1>

    <p>Gracias por tu interés en <?=$CFG->conference_name ?></p>

    <h3><a href="<?=$CFG->wwwroot ?>/register.php?context=ponente">Registro de ponentes</a>
    - <a href="<?=$CFG->wwwroot ?>/login.php?context=ponente">Accede a tu cuenta </a></h3>

    <p>Es necesario tu registro, mediante el cual podrás enviar ponencias y estar informado del evento.</p>

    <h3><a href="<?=$CFG->wwwroot ?>/register.php?context=asistente">Registro de asistentes </a>
    - <a href="<?=$CFG->wwwroot ?>/login.php?context=asistente">Accede a tu cuenta </a></h3>

    <p>Es necesario tu registro, mediante el cual podrás realizar preinscripción a <?=$CFG->conference_name ?>
    y  talleres/tutoriales además de mantenerte informado del evento.</p>

    <h3><a href="<?=$CFG->wwwroot ?>/lista/">Lista preliminar de ponencias</a></h3>

    <p>Aquí podrás ver las propuestas ponencias que han sido enviadas y el status en el que se encuentran dichas ponencias.</p>

    <h3><a href="<?=$CFG->wwwroot ?>/?q=proposals/info">Modalidades de participacion</a></h3>

    <p>Modalidades de las ponencias que encontraras en el evento!</p>
</div>

<?php
}

do_footer();
?> 
