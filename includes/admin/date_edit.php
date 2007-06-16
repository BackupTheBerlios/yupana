<?php
if (!defined('Context') || Context != 'admin') {
    header('Location: ' . get_url());
}

if (Action == 'newdate') {
    $date = new StdClass;    
}

else { // want to update the page
    preg_match('#^admin/dates/(\d+)/?$#', $q, $matches);
    $date_id= (!empty($matches)) ? (int) $matches[1] : 0;

    $date = get_record('fecha_evento', 'id', $date_id);
}

require($CFG->admdir . 'date_optional_params.php');

if (Action == 'newdate') {
?>

<h1>Añadir fecha para eventos</h1>

<?php } else { ?>

<h1>Modificar fecha para eventos</h1>

<?php
}

// process submit
if (!empty($submit)) {
    // messages holder
    $errmsg = array();

    require($CFG->admdir . 'date_optional_params_check.php');

    if (!empty($errmsg)) {
        show_error($errmsg);
    } else {
        // insert or update propuesta
        require($CFG->admdir . 'date_update_info.php');
        do_submit_cancel('', 'Continuar', $return_url);
    }
} 

if (empty($submit) || !empty($errmsg)) {
?> 

<form method="POST" action="">

    <p class="center"><em>Los campos marcados con asterisco(*) son obligatorios</em></p>

<?php
    include($CFG->admdir . 'date_input_table.php');

    if (Action == 'newdate') {
        do_submit_cancel('Registrar', 'Cancelar', $return_url);
    } else {
        do_submit_cancel('Guardar', 'Volver', $return_url);
    }
?>

</form>

<?php
}
?>
