<?php
// name of the conference
$config = get_config('conference_name');

$configs = array(
    'conference_name',
    'conference_link',
    'adminmail',
    'general_mail',
    'wwwroot'
    );

foreach ($configs as $config) {
    $cfg = get_config($config);

    if (empty($cfg->value)) {
        $cfg->value = $CFG->$config;
    }

    $$config = do_get_output('do_input', array($config, 'text', $cfg->value, 'size="30"'));
}

$values = array(
    'Nombre del Evento' => $conference_name,
    'URL del Evento' => $conference_link,
    'Email de Contacto' => $adminmail,
    'Email automático' => $general_mail,
    'URL del Sistema' => $wwwroot
    );

do_table_values($values, 'narrow');
?>

