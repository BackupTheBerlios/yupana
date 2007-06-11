<?php
require('header-common.php');

$q= optional_param('q');
switch ($q) {
    case 'kardex':
        require($CFG->incdir . 'common/user_kardex.php');
        break;

    case 'edit':
        require($CFG->incdir . 'common/user_edit.php');
        break;

    default: // default index
?> 

<h1>Asistentes</h1>
<h2>Bienvenido <?=$USER->nombrep ?> <?=$USER->apellidos ?></h2>

<div id="menuadmin">
    <div class="menuadmin column">
        <ul>
            <li><a href="<?=$CFG->wwwroot ?>/asistente/?q=edit">Modificar mis datos</a></li> 
            <li><a href="<?=$CFG->wwwroot ?>/asistente/?q=kardex">Imprimir hoja de registro</a></li>
            <li><a href="<?=$CFG->wwwroot ?>/asistente/?q=poll">Encuestas</a></li>
        </ul>
    </div>

    <div class="menuadmin column">
        <ul>
            <li><a href="asistente.php?opc=<?=LEVENTOS ?>">Listas eventos programados</a></li>
            <li><a href="asistente.php?opc=<?=LTALLERES ?>">Listar/Inscribirme a talleres y/o tutoriales</a></li>
            <li><a href="asistente.php?opc=<?=LTALLERESREG ?>">Listar/Baja de talleres y/o tutoriales registrados</a></li>
        </ul>
    </div>
</div>

<?php 
}

do_footer();
?>
