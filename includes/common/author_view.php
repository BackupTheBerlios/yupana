<?php
// basic check
if (empty($q) || empty($CFG)) {
    die;
}

if (Context == 'main') {
    $q = optional_param('q');
    $return_url = optional_param('return');

    preg_match('#^authors/view/(\d+)$#', $q, $matches);
    $author_id = (int) $matches[1];
}

$author = get_record('ponente', 'id', $author_id);

if (!empty($author)) {
?>

<h2 class="center"><?=$author->nombrep ?> <?=$author->apellidos ?></h2>

<?php
    do_table_values(array('Resumen Curricular' => $author->resume), 'narrow');

} else {
?> 

<p class="error">Usuario no encontrado</p>

<?php
}
?>

