<?php
// called directly?
if (empty($CFG)) {
    header('Location: ..');
}

// Globals
define('Context', 'ponente');
$return_url = get_url('speaker');

//init session
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
    define('Action', 'editdetails');

    do_header('Modificar información personal');
    include($CFG->comdir . 'user_edit.php');
}

// menu proposals list
elseif (preg_match('#^speaker/proposals/?$#', $q)) {
    define('Action', 'listproposals');
    //clear session return path
    $_SESSION['return_path'] = get_url('speaker/proposals');

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
    define('Action', 'viewproposal');
    if (!empty($_SESSION['return_path'])) {
        $return_url = $_SESSION['return_path'];
        //clear return path
        $_SESSION['return_path'] = '';
    } else {
        $return_url = get_url('speaker/proposals');
    }

    do_header('Detalles de propuesta');
    include($CFG->comdir . 'prop_view.php');
    do_submit_cancel('', 'Regresar', $return_url);
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
    define('Action', 'deleteproposal');

    do_header('Eliminar propuesta de ponencia');
    $return_url = $return_url . '/proposals';
    include($CFG->comdir . 'prop_delete.php');
}

/*
 * Files management
 *
 */

// file management
elseif (preg_match('#^speaker/proposals/\d+/files/?$#', $q)) {
    define('Action', 'proposalfiles');

    do_header('Adjuntos de la propuesta');
    $return_url = $return_url . '/proposals';
    include($CFG->comdir . 'prop_files.php');
}

// file download
elseif (preg_match('#^speaker/proposals/\d+/files/\d+/.+$#', $q)) {
    define('Action', 'downloadfile');
    include($CFG->comdir . 'prop_files_download.php');
}

// file download
elseif (preg_match('#^speaker/proposals/\d+/files/delete/\d+/?.*$#', $q)) {
    define('Action', 'deletefile');

    do_header('Eliminar archivo');
    include($CFG->comdir . 'prop_files_delete.php');
}

// file edit
elseif (preg_match('#^speaker/proposals/\d+/files/edit/\d+/?.*$#', $q)) {
    define('Action', 'editfile');

    do_header('Eliminar archivo');
    include($CFG->comdir . 'prop_files_edit.php');
}


/*
 * events
 *
 */

// list events 
elseif (preg_match('#^speaker/events/?$#', $q)) {
    define('Action', 'viewevents');

    do_header('Programa preliminar');
    $return_url = get_url('speaker');
?>

<h1>Lista de mis eventos programados</h1>

<?php
    include($CFG->admdir . 'event_list.php');
    do_submit_cancel('', 'Regresar', $return_url);
}

// page not found
else {
    do_header('Página no encontrada');
    include($CFG->tpldir . 'error_404.tmpl.php');
    do_submit_cancel('', 'Regresar');
}

// footer is called in main index
?>
