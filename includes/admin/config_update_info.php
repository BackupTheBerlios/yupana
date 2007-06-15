<?php
if (empty($cfg) || empty($submit) || Context != 'admin') {
    die; //exit
}

foreach ($configs as $config => $type) {
    set_config($config, $cfg->$config);
}

$errmsg[] = 'Información actualizada.';
?>
