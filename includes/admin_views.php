<?php
// called directly?
if (empty($CFG)) {
    header('Location: ..');
}

// Globals
define('Context', 'admin');
$return_url = get_url('admin');

// init session
require($CFG->comdir . 'session.php');

$q= optional_param('q');

// default index
if (preg_match('#^admin/?$#', $q)) {
    // default index
    do_header('Menu Administración');
    include($CFG->tpldir . 'admin_menu.tmpl.php');
}

/*
 * Routing
 *
 */

// add speaker
elseif (preg_match('#^admin/speakers/new$#', $q)) {
    define('Action', 'addspeaker');

    do_header('Agregar ponente');
    include($CFG->comdir . 'user_edit.php');
}

// list speakers
elseif (preg_match('#^admin/speakers/?$#', $q)) {
    define('Action', 'listspeakers');

    do_header('Listado de ponentes');
    include($CFG->comdir . 'speakers_list.php');
    do_submit_cancel('', 'Regresar', $return_url);
}

// view speaker details
elseif (preg_match('#^admin/speakers/\d+/?$#', $q)) {
    define('Action', 'viewspeaker');

    do_header('Detalles de ponente');
    include($CFG->comdir . 'speakers_view.php');
    do_submit_cancel('', 'Regresar', $return_url.'/speakers');
}

// add proposal
elseif (preg_match('#^admin/proposals/new$#', $q)) {
    define('Action', 'addproposal');

    do_header('Agregar ponencia');
    include($CFG->comdir . 'prop_edit.php');
}

// list proposals
elseif (preg_match('#^admin/proposals/?$#', $q)) {
    define('Action', 'listproposals');

    do_header('Listado de ponencias');
    include($CFG->comdir . 'prop_list.php');
}



// config manager
elseif (preg_match('#^admin/config/?$#', $q)) {
    define('Action', 'config');

    do_header('Configuración del Sistema');
    include($CFG->admdir . 'config_manager.php');
}

// config action
elseif (preg_match('#^admin/config/(open|close)/\d+$#', $q)) {
    define('Action', 'config');
    include($CFG->admdir . 'config_action.php');
}

// menu edit details
elseif (preg_match('#^admin/details$#', $q)) {
    define('Action', 'editdetails');

    do_header('Modificar información personal');
    include($CFG->comdir . 'user_edit.php');
}

// page not found
else {
    include($CFG->tpldir . 'error_404.tmpl.php');
}

// footer is called in main index
?>
