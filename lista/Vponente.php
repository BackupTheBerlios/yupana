<?php
require_once(dirname(dirname(__FILE__)) . '/includes/lib.php');

$idponente = optional_param('ponente', 0, PARAM_INT);
$return_url = optional_param('return');

do_header();
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
 
<?php
}

do_submit_cancel('', 'Regresar', $return_url);
do_footer();
?>
