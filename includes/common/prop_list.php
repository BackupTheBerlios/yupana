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
$order = "P.id_prop_tipo, P.id_ponente, P.reg_time";

if (Context == 'ponente') {
    // add filter, user own proposals
    $where .= " AND P.id_ponente={$USER->id}";
    // override order
    $order = 'P.id_status';
}

//run prop filters
include($CFG->comdir . 'prop_filter_optional_params.php');

$proposals = get_proposals($where, '', $order);

if (!empty($proposals)) {
?>

<h4>Ponencias registradas: <?=sizeof($proposals) ?></h4>

<?php
    // show prop filter form
    include($CFG->comdir . 'prop_filter.php');

    // build data table
    $table_data = array();

    if (Context == 'ponente') {
        $table_data[] = array('Ponencia', 'Tipo', 'Orientacion', 'Estado', '', '');
    } else {
        $table_data[] = array('Ponencia', 'Tipo', 'Orientacion', 'Estado');
    }

    foreach ($proposals as $proposal) {
        if (Context == 'ponente') {

            $url = get_url('speaker/proposals/'.$proposal->id);

            $l_ponencia = <<< END
<ul><li>
<a class="proposal" href="{$url}">{$proposal->nombre}</a>
</li></ul>
END;

            $l_delete = '';
            $l_modifiy = '';
            // only can cancel not deleted,acepted or scheduled proposals
            if ($proposal->id_status < 5) {
                $url = get_url('speaker/proposals/'.$proposal->id.'/delete');

                $l_delete = <<< END
<a class="precaucion" href="{$url}">Eliminar</a>
END;
                // dont update discarded proposals
                if ($proposal->id_status != 3 || $proposal->id_status != 6) {
                    $url = get_url('speaker/proposals/'.$proposal->id.'/update');

                    $l_modify = <<< END
<a class="verde" href="{$url}">Modificar</a>
END;

                }
            }
            
            $table_data[] = array(
                $l_ponencia,
                $proposal->tipo,
                $proposal->orientacion,
                $proposal->status,
                $l_delete,
                $l_modify
                );

        } else { // main
            $url = get_url('general/proposals/'.$proposal->id);
            $urlp = get_url('general/authors/'.$proposal->id_ponente);

            $l_ponencia = <<< END
<ul class="proposal">
<li><a href="{$url}">{$proposal->nombre}</a></li>
<ul class="speaker">
<li>{$proposal->nombrep} {$proposal->apellidos}</li>
</ul>
</ul>
END;

            $table_data[] = array(
                $l_ponencia,
                $proposal->tipo,
                $proposal->orientacion,
                $proposal->status
                );
        }

    }

    do_table($table_data, 'wide');

} else {
    if (Context == 'main') {
        $return_url = get_url('general/proposals');
    }
?>
<div class="block"></div>

<p class="error center"><?=$not_found_message ?></p>

<?php 
}
?>
