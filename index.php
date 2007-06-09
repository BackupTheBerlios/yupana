<?
	require_once('includes/lib.php');

    global $CFG;

	do_header();
?>
<div id="frontpage">

    <h1>Registro</h1>

    <p>Gracias por tu inter&eacute;s en <?=$CFG->conference_name ?></p>


 <h3><a href="<?=$CFG->wwwroot ?>/ponente/index.php?opc=<?=NPONENTE ?>">Registro de ponentes</a> - 
    <a href="<?=$CFG->wwwroot ?>/ponente/">Accede a tu cuenta </a></h3>

 <p>
 Es necesario tu registro, mediante el cual podr&aacute;s enviar
 ponencias y estar informado del evento.
</p>

<h3>
 <a href="<?=$CFG->wwwroot ?>/asistente/index.php?opc=<?=NASISTENTE ?>">Registro de asistentes </a> - <a href="<?=$CFG->wwwroot ?>/asistente/">Accede a tu cuenta </a></h3>

 <p>
 Es necesario tu registro, mediante el cual podr&aacute;s realizar preinscripci&oacute;n al al congreso y  talleres
 ademas de mantenerte informado del evento.
</p>

<h3><a href="<?=$CFG->wwwroot ?>/lista/">Lista preliminar de ponencias</a></h3>

<p>Aqu&iacute; ver&aacute;s las propuestas ponencias que han sido enviadas, y el status en el que se encuentran dichas ponencias.</p>

<h3><a href="<?=$CFG->wwwroot ?>/modalidades/">Modalidades de participacion</a></h3>
<p>
Modalidades de las ponencias que encontraras en el evento!
</p>
 
</div>

<?=do_footer();?> 
