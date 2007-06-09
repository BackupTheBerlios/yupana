<?php
require_once('header-common.php');
$rooms = get_records('lugar');
?>

<h1>Listado de lugares para eventos</h1>

<table class="wide">
    <tr class="table-headers">

    <td>Nombre</td>
    <td>Ubicación</td>
    <td>Cupo</td>
    <td>Eventos</td>

<?php
	if ($_SESSION['YACOMASVARS']['rootlevel']==1 ) {
?>
	<td></td>
<?php
    }
?>
    </tr>

<?php
    if (!empty($rooms)) {
        $trclass = 'even';
        foreach ($rooms as $room) {
?>
    <tr class=<?=($trclass == 'even') ? 'even' : 'odd' ?>>
    <td><a class="verde" href="Mlugar.php?idlugar=<?=$room->id ?>"><?=$room->nombre_lug ?></a></td>
    <td><?=$room->ubicacion ?></td>
    <td><?=($room->cupo != 99999) ? $room->cupo : '' ?></td>
    <td><a class="azul" href="Leventos-lugar.php?idlugar=<?=$room->id ?>">Eventos Registrados</a></td>

<?php
            if ($_SESSION['YACOMASVARS']['rootlevel']==1 ) {
?>

    <td><a class="precaucion" href="Blugar.php?idlugar=<?=$room->id ?>">Eliminar</a></td>

<?php
            }
?>
    </tr>

<?php
            $trclass = ($trclass=='even') ? 'odd' : 'even';
        }
    }
?>
</table>

<p id="buttons">
    <input type="button" value="Volver al menu" onClick="location.href='<?=$CFG->wwwroot ?>/admin/menuadmin.php#lugares'" />
</p>

<?php
    do_footer();
?>
