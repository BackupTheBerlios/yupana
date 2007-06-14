<?php
// called directly?
if (empty($CFG)) {
    header('Location: ..');
}

// Globals
define('Context', 'ponente');
$return_url = get_url('speaker');

// init session
require($CFG->comdir . 'session.php');

$q= optional_param('q');

// default index
if (preg_match('#^speaker/?$#', $q)) {
    // default index
    do_header('Menu Ponentes');
    include($CFG->tpldir . 'speaker_menu.tmpl.php');
}

/*
 * Routing
 *
 */

// menu edit details
elseif (preg_match('#^speaker/details$#', $q)) {
    do_header('Modificar información personal');
    include($CFG->comdir . 'user_edit.php');
}

// menu proposals list
elseif (preg_match('#^speaker/proposals/?$#', $q)) {
    do_header('Lista de propuestas enviadas');

?>  <h1>Lista de ponencias enviadas</h1> <?php

    include($CFG->comdir . 'prop_list.php');
    do_submit_cancel('', 'Regresar', $return_url);
}

// menu add proposals
elseif (preg_match('#^speaker/proposals/new$#', $q)) {
    define('Action', 'newproposal');
    do_header('Enviar propuesta de ponencia');
    include($CFG->comdir . 'prop_edit.php');
}

// view proposals details
elseif (preg_match('#^speaker/proposals/\d+/?$#', $q)) {
    do_header('Detalles de propuesta');
    include($CFG->comdir . 'prop_view.php');
    do_submit_cancel('', 'Regresar', $return_url.'/proposals');
}

// edit proposals details
elseif (preg_match('#^speaker/proposals/\d+/update$#', $q)) {
    define('Action', 'updateproposal');
    do_header('Modificar propuesta');
    $return_url = $return_url.'/proposals'; // back to proposals list
    include($CFG->comdir . 'prop_edit.php');
}

// delete proposal
elseif (preg_match('#^speaker/proposals/(\d+)/delete$#', $q)) {
    do_header('Eliminar propuesta de ponencia');
    $return_url = $return_url . '/proposals';
    include($CFG->comdir . 'prop_delete.php');
}

// page not found
else {
    include($CFG->tpldir . 'error_404.tmpl.php');
}

// footer is called in main index
?>
