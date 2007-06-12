<?php
// basic check
if (empty($q) || empty($CFG)) {
    die;
}

if (Context == 'main') {
    $q = optional_param('q');
    $return_url = optional_param('return');

    preg_match('#^general/authors/(\d+)$#', $q, $matches);
    $author_id = (!empty($matches)) ? (int) $matches[1] : 0;
}

$author = get_record('ponente', 'id', $author_id);

if (!empty($author)) {
?>

<h2 class="center"><?=$author->nombrep ?> <?=$author->apellidos ?></h2>

<?php
    do_table_values(array('Resumen Curricular' => $author->resume), 'narrow');

} else {
?> 

<h1>Usuario no encontrado</h1>

<div class="block"></div>

<p class="center">El usuario que buscas no existe.</p>

<?php
}
?>

