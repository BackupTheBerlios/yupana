<?php
// basic check
if (empty($q) || empty($CFG) || Context != 'admin') {
    die;
}

if (Action == 'viewspeaker') {
    preg_match('#^admin/speakers/(\d+)/?$#', $q, $matches);
    $speaker_id = (!empty($matches)) ? (int) $matches[1] : 0;

    $user = get_speaker($speaker_id);
    $desc = 'Ponente';
    $prop_desc = 'Propuestas enviadas';
}

elseif (Action == 'viewperson') {
    preg_match('#^admin/persons/(\d+)/?$#', $q, $matches);
    $person_id = (!empty($matches)) ? (int) $matches[1] : 0;

    $user = get_person($person_id);
    $desc = 'Asistente';
    $prop_desc = 'Talleres/Tutoriales inscritos';
}

if (!empty($user)) {
?>

<h1>Datos de <?=$desc ?></h1>

<?php
    include($CFG->comdir . 'user_display_info.php');
?>

<h2 class="center"><?=$prop_desc ?></h2>

<?php
    include($CFG->comdir . 'prop_list.php');
} else {
?>

<h1>Usuario no encontrado</h1>

<div class="block"</div>

<p class="error center">El usuario que buscas no existe.</p>

<?php 
}
?>
