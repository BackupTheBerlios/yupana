<?php
require('../includes/lib.php');

do_header();

//FIXME: better use own variables for each arg
$vopc = optional_param('vopc');
$tok = strtok ($_GET['vopc']," ");
$idponente = (int)$tok;

$tok = strtok (" ");
$regresa='';
	while ($tok) {
		$regresa .= $tok;
		$tok=strtok(" ");
	}
?>

<h1>Datos de ponente</h1>

<?php
$user = get_record('ponente', 'id', $idponente);

if (!empty($user)) {
   
?>

<h2 class="center"><?=$user->nombrep ?> <?=$user->apellidos ?></h2>

<?php
    if (empty($user->resume)) {
        $user->resume = '--';
    }

    $values = array(
        'Resumen Curricular' => $user->resume,
    );

    do_table_values($values, 'narrow');

} else {
?>

<p class="error center">Usuario no encontrado</p>
 
<?php } ?>

<p id="buttons">
    <input type="submit" value="Regresar" onClick="location.href='<?=$regresa ?>'" />
</p>

<?php
do_footer();
?>
