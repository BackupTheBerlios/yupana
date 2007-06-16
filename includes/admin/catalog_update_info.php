<?php
// running directly?
if (empty($CFG) || $USER->id_tadmin != 1 || empty($catalog)) {
    die;
}

if (!empty($datas)) {
    foreach ($datas as $data) {
        $rs = update_record($catalog, $data);

        if (!$rs) {
            $errmsg[] = 'Hubo un error al actualizar los datos.';
        } else {
             $ok = true;
        }
    }

    if ($ok) {
        $errmsg[] = 'Datos actualizados.';
    }
}

?>
