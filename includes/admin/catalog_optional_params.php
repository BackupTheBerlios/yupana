<?php
// running directly?
if (empty($CFG) || $USER->id_tadmin != 1 || empty($catalog)) {
    die;
}

$datas = array();

$records = get_records($catalog);

//for ($i=1; $i<=$n; $i++) {
foreach ($records as $record) {

    $data = new StdClass;
    $data->id = $record->id;

    $input = optional_param($catalog.$record->id);

    if ($catalog == 'tadmin') {
        $data->tareas = $input;
        $datas[] = $data;
    }

    else {
        $data->descr = $input;

        if (empty($data->descr)) {
            $errmsg[] = 'No puedes dejar vacía ninguna descripción.';
        } else {
            $datas[] = $data;
        }
    }

}
?>
