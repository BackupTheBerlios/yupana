<?php
// dummy check
if (empty($CFG)) {
    die;
}

if (empty($not_found_message)) {
    $not_found_message = "No se encontro ninguna propuesta registrada.";
}

// default where
$where = 'P.id_status != 7';

// default order
$order = "P.act_time DESC, P.reg_time DESC, P.id_prop_tipo, P.id_ponente";

if (Context == 'admin') {
    if (Action == 'listdeletedproposals') {
        // status deleted
        $where ='P.id_status = 7';
    }
   
    elseif (Action == 'scheduleevent') {
        // list ready to program proposals
        $where = 'P.id_status = 5';
        $order = 'P.id_ponente';
    }
   
    elseif (Action == 'viewspeaker') {
        //list speaker own proposals
        $where = 'P.id_ponente='.$user->id;
    }

    else {
        // default status !deleted !programmed
        $where = "P.id_status < 7";
    }
}

elseif (Context == 'ponente') {
    // add filter, user own proposals
    $where .= " AND P.id_ponente={$USER->id}";
    // override order
    $order = 'P.id_status';
}

elseif (Context == 'main') {
    // order by reg_time;
    $order = 'P.reg_time, P.id_prop_tipo, P.id';
}

//run prop filters
include($CFG->comdir . 'prop_filter_optional_params.php');

$proposals = get_proposals($where, '', $order);

if (!empty($proposals)) {
?>

<h4>Ponencias listadas: <?=sizeof($proposals) ?></h4>

<?php
    // show prop filter form
    include($CFG->comdir . 'prop_filter.php');

    // build data table
    $table_data = array();

    if (Context == 'ponente') {
        $table_data[] = array('Ponencia', 'Tipo', 'Orientación', 'Estado', '', '');
    }
   
    elseif (Context == 'admin') {
        if (Action == 'listproposals') {
            $table_data[] = array('Ponencia', 'Tipo', 'Status', 'Asignado', '');
        } 

        elseif (Action == 'listdeletedproposals') {
            $table_data[] = array('Ponencia', 'Modificado por', 'Fecha de Modif.', 'Tipo', 'Ponente');
        }

        elseif (Action == 'scheduleevent') {
            $table_data[] = array('Ponencia', 'Tipo', 'Orientación', '');
        }

        elseif (Action == 'viewspeaker') {
            $table_data[] = array('Ponencia', 'Tipo', 'Status', 'Adjuntos');
        }

        $status_list = get_records_select('prop_status', 'id < 7');
    }

    else {
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
            $l_modify = '';
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

        }
       
        // admin
        elseif (Context == 'admin') {
            $urlp = get_url('admin/speakers/'.$proposal->id_ponente);

            if (Action == 'listproposals' || Action == 'scheduleevent' || Action == 'viewspeaker') {

                $url = get_url('admin/proposals/'.$proposal->id);

                if (Action == 'viewspeaker') {
                    $l_ponencia = <<< END
<ul class="proposal">
<li><a href="{$url}">{$proposal->nombre}</a></li>
</ul>
END;
                }

                else {

                $l_ponencia = <<< END
<ul class="proposal">
<li><a href="{$url}">{$proposal->nombre}</a></li>
<ul class="speaker">
<li><a href="{$urlp}">{$proposal->nombrep} {$proposal->apellidos}</a></li>
</ul>
</ul>
END;
                }

            } elseif (Action == 'listdeletedproposals') {

                $url = get_url('admin/proposals/deleted/'.$proposal->id);

                $l_ponencia = <<< END
<ul class="proposal">
<li><a href="{$url}">{$proposal->nombre}</a></li>
</ul>
END;

                $l_ponente = <<< END
                <strong>{$proposal->login}</strong><br />
                <small><a href="{$urlp}">{$proposal->nombrep} {$proposal->apellidos}</a></small>
END;

            }

            if (Action != 'scheduleevent' && Action != 'viewspeaker') {
                $actions = '<ul class="list-vmenu">';

                foreach ($status_list as $stat) {
                    if ($stat->id == $proposal->id_status) {

                        $actions .= "<li class=\"admin-actions\">{$stat->descr}</li>";

                    } else {
                        if (Action == 'listproposals') {
                            $urla = get_url('admin/proposals/'.$proposal->id.'/status/'.$stat->id);
                        }

                        elseif (Action == 'listdeletedproposals') {
                            $urla = get_url('admin/proposals/deleted/'.$proposal->id.'/status/'.$stat->id);
                        }

                        $actions .= "<li class=\"admin-actions\"><a class=\"verde\" href=\"{$urla}\">{$stat->descr}</a></li>";
                    }
                }

                $actions .= '</ul>';
                $l_ponencia .= $actions;

            }

            if (Action == 'listdeletedproposals') {
                $adminuser = empty($proposal->adminlogin) ? 'Usuario' : $proposal->adminlogin;
            } else {
                $adminuser = empty($proposal->adminlogin) ? 'Ninguno' : $proposal->adminlogin;
            }

            if (Action == 'listproposals') {
            $url = get_url('admin/proposals/'.$proposal->id.'/delete');
            $l_delete = <<< END
<a class="precaucion" href="{$url}">Eliminar</a>
END;
            }

            if (Action == 'scheduleevent') {
                $url = get_url('admin/events/schedule/'.$proposal->id);
                $l_event = "<a class=\"verde\" href=\"{$url}\">Asignar lugar</a>";
            }

            if (Action == 'listproposals') {
                $table_data[] = array(
                    $l_ponencia,
                    $proposal->tipo,
                    $proposal->status,
                    $adminuser,
                    $l_delete
                    );
            } elseif (Action == 'listdeletedproposals') {
                $table_data[] = array(
                    $l_ponencia,
                    $adminuser,
                    $proposal->act_time,
                    $proposal->tipo,
                    $l_ponente
                    );
            } elseif (Action == 'scheduleevent') {
                $table_data[] = array(
                    $l_ponencia,
                    $proposal->tipo,
                    $proposal->orientacion,
                    $l_event
                    );
            } elseif (Action == 'viewspeaker') {
                $table_data[] = array(
                    $l_ponencia,
                    $proposal->tipo,
                    $proposal->status,
                    ''
                    );
            }
        } 
       
        else { // main
            $url = get_url('general/proposals/'.$proposal->id);

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
    } elseif (Context == 'admin') {
        if (Action == 'listdeletedproposals') {
            $return_url = get_url('admin');
        } else {
            $return_url = get_url('admin/proposals');
        }
    }
?>
<div class="block"></div>

<p class="error center"><?=$not_found_message ?></p>

<?php 
}
?>
