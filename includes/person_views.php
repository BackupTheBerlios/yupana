<?php
// called directly?
if (empty($CFG)) {
    header('Location: ..');
}

// Globals
define('Context', 'asistente');
$return_url = get_url('person');

//init session
require($CFG->comdir . 'session.php');

$q= optional_param('q');

// default index
if (preg_match('#^person/?$#', $q)) {
    // default index
    do_header(__('Menu asistentes'));
    include($CFG->tpldir . 'person_menu.tmpl.php');
}

/*
 * Routing
 *
 */

// menu edit details
elseif (preg_match('#^person/details$#', $q)) {
    define('Action', 'editdetails');

    do_header(__('Modificar información personal'));
    include($CFG->comdir . 'user_edit.php');
}

/*
 * events
 *
 */

// list events 
elseif (preg_match('#^person/events/?$#', $q)) {
    define('Action', 'viewevents');

    do_header(__('Programa preliminar'));
?>

<h1><?=__('Lista de eventos programados') ?></h1>

<?php
    include($CFG->admdir . 'event_list.php');
    do_submit_cancel('', __('Regresar'), $return_url);
}

// kardex events
elseif (preg_match('#^person/kardex/?$#', $q)) {
    define('Action', 'userkardex');

    do_header(__('Hoja de Registro'));
    include($CFG->comdir .'user_kardex.php');
}

// view proposals details
elseif (preg_match('#^person/proposals/\d+/?$#', $q)) {
    define('Action', 'viewproposal');
    if (!empty($_SESSION['return_path'])) {
        $return_url = $_SESSION['return_path'];
        //clear return path
        $_SESSION['return_path'] = '';
    } else {
        $return_url = get_url('person/events');
    }

    do_header(__('Detalles de propuesta'));
    include($CFG->comdir . 'prop_view.php');
}

// file download
elseif (preg_match('#^person/proposals/\d+/files/\d+/.+$#', $q)) {
    define('Action', 'downloadfile');
    include($CFG->comdir . 'prop_files_download.php');
}

// page not found
else {
    do_header(__('Página no encontrada'));
    include($CFG->tpldir . 'error_404.tmpl.php');
    do_submit_cancel('', __('Regresar'));
}

// footer is called in main index
?>
