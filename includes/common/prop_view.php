<?php
// basic check
if (empty($q) || empty($CFG)) {
    die;
}

// where we are?
if (Context == 'ponente') {
    preg_match('#^speaker/proposals/(\d+)/?$#', $q, $matches);
    $proposal_id = (!empty($matches)) ? (int) $matches[1] : 0;

    $proposal = get_proposal($proposal_id, $USER->id);
}

elseif (Context == 'admin') {
    if (Action == 'viewproposal') {
        preg_match('#^admin/proposals/(\d+)/?#', $q, $matches);
        $proposal_id = (!empty($matches)) ? (int) $matches[1] : 0;
    }

    elseif (Action == 'viewdeletedproposal') {
        preg_match('#^admin/proposals/deleted/(\d+)/?#', $q, $matches);
        $proposal_id = (!empty($matches)) ? (int) $matches[1] : 0;
    }

    $proposal = get_proposal($proposal_id);
}

elseif (Context == 'asistente') {
    preg_match('#^person/proposals/(\d+)/?$#', $q, $matches);
    $proposal_id = (!empty($matches)) ? (int) $matches[1] : 0;

    $proposal = get_proposal($proposal_id);
}

elseif (Context == 'main') {
    preg_match('#^general/proposals/(\d+)/?$#', $q, $matches);
    $proposal_id = (!empty($matches)) ? (int) $matches[1] : 0;

    $proposal = get_proposal($proposal_id);
}

if (!empty($proposal)) {
?>

<h1 class="proposal-title left"><?=$proposal->nombre ?></h1>
<h2 class="proposal-details left">Detalles de la ponencia</h2>

<?php
    include($CFG->comdir . 'prop_display_info.php');

    if (Context != 'ponente' && Context != 'admin') {
?>

<h2 class="proposal-details left">Detalles de los autores</h2>

<ul class="speaker-details">
<li>
<p class="speaker"><?=$proposal->nombrep ?> <?=$proposal->apellidos ?>
    <br /><?=$proposal->org ?></p>
<p class="resume"><?=nl2br($proposal->resume) ?></li>
</ul>

<?php
     }
} else {
?>

<h1>Propuesta no encontrada</h1>

<div class="block"></div>

<?php if (Context == 'ponente') { ?>

<p class="error center">No se encontro la propuesta en tus registros.</p>

<?php } else { ?>

<p class="error center">Registros de propuesta no encontrados. Posiblemente no exista
o no tengas acceso al registro.</p>

<?php
    }
}
?>
