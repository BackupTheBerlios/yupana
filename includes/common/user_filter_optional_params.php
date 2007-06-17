<?php

    // input variables
    $id_estado = optional_param('filter_id_estado', 0, PARAM_INT);
    $id_estudios = optional_param('filter_id_estudios', 0, PARAM_INT);

    // modify sql where
    if (!empty($id_estado)) {
        if (Action == 'listspeakers') {
            $where .= ' AND SP.id_estado='.$id_estado;
        }

        elseif (Action == 'listpersons') {
            $where .= ' AND P.id_estado='.$id_estado;
        }
    }

    if (!empty($id_estudios)) {
        if (Action == 'listspeakers') {
            $where .= ' AND SP.id_estudios='.$id_estudios;
        }

        elseif (Action == 'listpersons') {
            $where .= ' AND P.id_estudios='.$id_estudios;
        }
    }
?>
