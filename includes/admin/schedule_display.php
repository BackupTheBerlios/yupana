<?php
// FIXME: ugly code

$trfirst = true;
$even = true;

$width = round((100) / sizeof($rooms));
?>

<table id="schedule-table"> 

<?php
$hour = 1;

$ignore = array();

foreach ($table_data as $row) {

    if ($trfirst) {
?>

    <tr class="table-headers schedule-rooms">

<?php } else { ?>
    
    <tr class="hour-<?=$hour ?> <?=($even) ? 'even' : 'odd' ?>">

<?php 
        $hour++;
    }

    $ncol = 1;
    foreach ($row as $prop) {

        if (empty($prop)) {
            $column = '&nbsp;';

        } elseif (is_object($prop)) {
            if (Context == 'admin' && !empty($prop->date_id)) {
                $url = get_url('admin/schedule');
                $url .= '/add/'.$prop->room_id.'/'.$prop->date_id.'/'.$prop->hour;

                $column  = <<< END
<a class="littleinfo" href="{$url}">Añadir Evento</a>
END;

            } else {
                $url = get_url('general/proposals') . '/' . $prop->id;

                $column = <<< END
    <a href="{$url}">{$prop->nombre}</a>
    <p class="littleinfo">{$prop->nombrep} {$prop->apellidos}</p>
END;

            }
        } else {
            $column = $prop;
        }

        if ($ncol != 1) {
            $tdwidth = 'style="width:'.$width.'%;"';
        } else {
            $tdwidth = '';
        }

        $rowspan = '';
        if (!empty($prop->duracion)) {
            if (!empty($ignore[$prop->id])) {
                $ignore[$prop->id] -= 1;
                $column = '';

            } else {
                $rowspan = "rowspan=\"{$prop->duracion}\"";
                $ignore[$prop->id] = $prop->duracion-1;
            }
        }

        if (!empty($prop->id_orientacion)) {
            $extraclass = 'track-' . $prop->id_orientacion;
        } else {
            $extraclass = '';
        }

        if (!empty($column)) {
            if ($trfirst) {
?>

    <td class="room-<?=$ncol ?> <?=$extraclass ?>" <?=$tdwidth ?>><?=$column ?></td>

<?php
            } else {
            
?> 
    <td class="column-<?=$ncol ?> <?=$extraclass ?>" <?=$tdwidth ?> <?=$rowspan ?>><?=$column ?></td>

<?php
            }
        }
        $ncol++;
    }
?>
    </tr>
<?php
    if ($trfirst) {
        $trfirst = false;
    }

    $even = ($even) ? false : true;
}
?>

</table>
