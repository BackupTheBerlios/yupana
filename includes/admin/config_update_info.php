<?php
if (empty($cfg) || empty($submit) || $USER->id_tadmin != 1) {
    die; //exit
}

foreach ($configs as $config => $type) {
    set_config($config, $cfg->$config);
}

$errmsg[] = 'Información actualizada.';
?>
