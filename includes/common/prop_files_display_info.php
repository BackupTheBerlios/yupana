<?php
    // running from system?
    if (empty($CFG) || empty($file)) {
        die;
    }
   
    $values = array(
            'Propuesta' => $proposal->nombre,
            'Título' => $file->title,
            'Descripción' => $file->descr,
            'Nombre de archivo' => $file->name,
            'Publico' => (empty($file->public)) ? 'No' : 'Si'
        );

    do_table_values($values, 'narrow');
?>
