<?php
// menu principal asistentes
require_once('common/asistente_header.php');

// who am i?
$idasistente = $_SESSION['YACOMASVARS']['asiid'];

// user data
$user = get_record('asistente', 'id', $idasistente);

?>
<h1>Asistentes</h1>
<h2>Bienvenido <?=$user->nombrep ?> <?=$user->apellidos ?></h2>

<div id="menuadmin">
    <div class="menuadmin column">
        <ul>
            <li><a href="asistente.php?opc=<?=MASISTENTE ?>">Modificar mis datos</a></li> 
            <li><a href="asistente.php?opc=<?=HOJAREGISTRO ?>">Imprimir hoja de registro</a></li>
            <li><a href="asistente.php?opc=<?=ENCUESTA ?>">Encuestas</a></li>
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
do_footer();
?>
