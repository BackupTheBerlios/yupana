<h1>Asistentes</h1>

<h2>Bienvenido <?=$USER->nombrep ?> <?=$USER->apellidos ?></h2>

<div id="menuadmin">

    <div class="menuadmin column">

        <ul>

            <li><a href="<?=$CFG->wwwroot ?>/edit.php?context=asistente">Modificar mis datos</a></li> 
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
