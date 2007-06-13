<?php
// dummy check
if (empty($CFG)) {
    die;
}

if (empty($not_found_message)) {
    $not_found_message = "No se encontro ninguna propuesta registrada.";
}

// default status !deleted
$where = "P.id_status != 7";
// default order
$order = "ORDER BY P.id_prop_tipo, P.id_ponente, P.reg_time";

if (Context == 'ponente') {
    // add filter, user own proposals
    $where .= " AND P.id_ponente={$USER->id}";
    // override order
    $order = "ORDER BY P.id_status";
}

$query = '
    SELECT P.id AS id_ponencia,
        P.nombre AS ponencia,
        P.id_prop_tipo,
        P.id_ponente,
        P.id_status,
        PO.nombrep,
        PO.apellidos,
        PT.descr AS prop_tipo,
        O.descr AS orientacion,
        S.descr AS status
    FROM propuesta P
    JOIN ponente PO ON P.id_ponente = PO.id
    JOIN prop_tipo PT ON P.id_prop_tipo = PT.id
    JOIN orientacion O ON P.id_orientacion = O.id
    JOIN prop_status S ON P.id_status = S.id
    WHERE '. $where .' '. $order;

$proposals = get_records_sql($query);

if (!empty($proposals)) {
    // build data table
    $table_data = array();

    if (Context == 'ponente') {
        $table_data[] = array('Ponencia', 'Tipo', 'Orientacion', 'Estado', '', '');
    } else {
        $table_data[] = array('Ponencia', 'Tipo', 'Orientacion', 'Estado');
    }

    foreach ($proposals as $proposal) {
        if (Context == 'ponente') {

            $l_ponencia = <<< END
<a class="proposal" href="{$CFG->wwwroot}/?q=author/proposals/{$proposal->id_ponencia}">{$proposal->ponencia}</a>
END;

            $l_delete = '';
            $l_modifiy = '';
            // only can cancel not deleted,acepted or scheduled proposals
            if ($proposal->id_status < 5) {
                $l_delete = <<< END
<a class="precaucion" href="{$CFG->wwwroot}/?q=author/proposals/{$proposal->id_ponencia}/delete">Eliminar</a>
END;
                // dont update discarded proposals
                if ($proposal->id_status != 3 || $proposal->id_status != 6) {
                    $l_modify = <<< END
<a class="verde" href="{$CFG->wwwroot}/?q=author/proposals/{$proposal->id_ponencia}/update">Modificar</a>
END;

                }
            }
            
            $table_data[] = array(
                $l_ponencia,
                $proposal->prop_tipo,
                $proposal->status,
                $l_delete,
                $l_modify
                );

        } else { // main

            $l_ponencia = <<< END
<a class="proposal" href="{$CFG->wwwroot}/?q=general/proposals/{$proposal->id_ponencia}&return={$request_uri}">{$proposal->ponencia}</a>
<br />
<a class="author" href="{$CFG->wwwroot}/?q=general/authors/{$proposal->id_ponente}&return={$request_uri}">{$proposal->nombrep} {$proposal->apellidos}</a>
END;

            $table_data[] = array(
                $l_ponencia,
                $proposal->prop_tipo,
                $proposal->orientacion,
                $proposal->status
                );
        }

    }

    do_table($table_data, 'wide');

} else {
?>
<div class="block"></div>

<p class="error center"><?=$not_found_message ?></p>

<?php 
}
?>
