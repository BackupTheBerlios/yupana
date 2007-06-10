<?php
require_once('../includes/lib.php');

beginSession('A');
session_unset();
session_destroy();

do_header();
?>

<h1>Salida de sesión Asistente</h1>

<p class="center">Usted ha sido salido exitosamente del sistema.</p>

<p id="buttons">
    <input type="button" value="Regresar" onClick="location.href='../'" />
</p>

<?php
do_footer();
?>
