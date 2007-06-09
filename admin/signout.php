<?
require_once("../includes/lib.php");

beginSession('R');
session_unset();
session_destroy();

do_header();
?>

<div class="center">
    <h1>Salida de sesion Administrador</h1>
    <p>Usted ha sido desconectado del sistema.</p>
    <div id="buttons">
        <input type="button" value="Regresar" onClick=location.href="../">
    </div>
</div>

<?php
do_footer();
?>
