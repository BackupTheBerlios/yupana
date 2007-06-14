<?php
if (!defined('Context') || empty($CFG) || empty($q)
    || (Context != 'ponente' && Context != 'admin')) {

    header('Location: ' . get_url());
}

if (Action == 'newproposal') {
    //    
}

else { // want to update the page
    preg_match('#^speaker/proposals/(\d+)/update$#', $q, $matches);
    $proposal_id = (!empty($matches)) ? (int) $matches[1] : 0;

    $proposal = get_record('propuesta', 'id', $proposal_id);
}

if (empty($proposal)) {
    //initialize proposal
    $proposal = new StdClass;
}

require($CFG->comdir . 'prop_optional_params.php');

if (Action == 'newproposal') {
?>

<h1>Nueva propuesta</h1>

<?php } else { ?>

<h1>Modificar ponencia</h1>

<?php
}


// process submit
if (!empty($submit)) {
    // check if register is open
    require($CFG->comdir . 'register_flag_check.php');
    // messages holder
    $errmsg = array();

    require($CFG->comdir . 'prop_optional_params_check.php');

    if (!empty($errmsg)) {
        show_error($errmsg);
    } else {
        // insert or update propuesta
        require($CFG->comdir . 'prop_update_info.php');

        do_submit_cancel('', 'Continuar', $return_url);
    }
}

if (empty($submit) || !empty($errmsg)) { // show form
?> 

<form method="POST" action="">

    <p class="center"><em>Los campos marcados con asterisco(*) son obligatorios</em></p>

<?php
    include($CFG->comdir . 'prop_input_table.php');

    if (Action == 'newproposal') {
        do_submit_cancel('Registrar', 'Cancelar', $return_url);
    } else {
        do_submit_cancel('Actualizar', 'Cancelar', $return_url);
    }
?>

</form>

<?php
}
?>


