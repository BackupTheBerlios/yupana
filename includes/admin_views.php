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
    define('Action', 'newspeaker');

    do_header('Agregar ponente');
    include($CFG->comdir . 'user_edit.php');
}

// list speakers
elseif (preg_match('#^admin/speakers/?$#', $q)) {
    define('Action', 'listspeakers');

    do_header('Listado de ponentes');
    include($CFG->comdir . 'user_list.php');
    do_submit_cancel('', 'Regresar', $return_url);
}

// list speakers
elseif (preg_match('#^admin/persons/?$#', $q)) {
    define('Action', 'listpersons');

    do_header('Listado de asistentes');
    include($CFG->comdir . 'user_list.php');
    do_submit_cancel('', 'Regresar', $return_url);
}

// view speaker details
elseif (preg_match('#^admin/speakers/\d+/?$#', $q)) {
    define('Action', 'viewspeaker');

    do_header('Detalles de ponente');
    include($CFG->comdir . 'user_view.php');
    do_submit_cancel('', 'Regresar');
}

// add proposal
elseif (preg_match('#^admin/proposals/new$#', $q)) {
    define('Action', 'newproposal');

    do_header('Agregar ponencia');
    include($CFG->comdir . 'prop_edit.php');
}

// list proposals
elseif (preg_match('#^admin/proposals/?$#', $q)) {
    define('Action', 'listproposals');

    do_header('Listado de ponencias');

?> <h1>Lista de propuestas enviadas</h1> <?php

    include($CFG->comdir . 'prop_list.php');
    do_submit_cancel('', 'Regresar', $return_url);
}

// view proposal
elseif (preg_match('#^admin/proposals/\d+/?$#', $q)) {
    define('Action', 'viewproposal');

    do_header('Detalles de ponencia');
    include($CFG->comdir . 'prop_view.php');
    do_submit_cancel('', 'Regresar');
}

// update status of proposal
elseif (preg_match('#^admin/proposals/\d+/status/\d+/?$#', $q)) {
    define('Action', 'viewproposal');
    $return_url = get_url('admin/proposals');

    include($CFG->admdir . 'prop_action.php');
}

// list deleted proposals
elseif (preg_match('#^admin/proposals/deleted/?$#', $q)) {
    define('Action', 'listdeletedproposals');

    do_header('Ponencias eliminadas');

?> <h1>Lista de ponencias eliminadas</h1> <?php

    include($CFG->comdir . 'prop_list.php');
    do_submit_cancel('', 'Regresar', $return_url);
}

// view deleted proposal
elseif (preg_match('#^admin/proposals/deleted/\d+/?$#', $q)) {
    define('Action', 'viewdeletedproposal');

    do_header('Detalles de ponencia');
    include($CFG->comdir . 'prop_view.php');
    do_submit_cancel('', 'Regresar', get_url('admin/proposals/deleted'));
}

// change status of  deleted proposals
elseif (preg_match('#^admin/proposals/deleted/\d+/status/\d+/?$#', $q)) {
    define('Action', 'deletedproposal');
    include($CFG->admdir . 'prop_action.php');
}

// delete proposal
elseif (preg_match('#^admin/proposals/\d+/delete$#', $q)) {
    define('Action', 'deleteproposal');

    do_header('Eliminar ponencia');
    include($CFG->comdir . 'prop_delete.php');
}

/*
 * Rooms Management
 * 
 */

// room add
elseif (preg_match('#^admin/rooms/new$#', $q)) {
    define('Action', 'newroom');

    do_header('Agregar lugar');
    include($CFG->admdir . 'room_edit.php');
}

// rooms list
elseif (preg_match('#^admin/rooms/?$#', $q)) {
    define('Action', 'listrooms');

    do_header('Lista de lugares para eventos');
    include($CFG->admdir . 'room_list.php');
    do_submit_cancel('', 'Volver al Menu', get_url('admin'));
}

// room edit
elseif (preg_match('#^admin/rooms/\d+/?$#', $q)) {
    define('Action', 'editroom');
    $return_url = get_url('admin/rooms');

    do_header('Editar lugar');
    include($CFG->admdir . 'room_edit.php');
}

// room add
elseif (preg_match('#^admin/rooms/\d+/delete$#', $q)) {
    define('Action', 'deleteroom');
    $return_url = get_url('admin/rooms');

    do_header('Eliminar lugar');
    include($CFG->admdir . 'room_delete.php');
}

// room events
elseif (preg_match('#^admin/rooms/\d+/events$#', $q)) {
    define('Action', 'eventsroom');
    $return_url = get_url('admin/rooms');

    do_header('Eventos del lugar');

?> <h1>Lista de eventos por lugar</h1> <?php

    include($CFG->admdir . 'event_list.php');
    do_submit_cancel('', 'Regresar', $return_url);
}

/*
 * Dates Management
 *
 */

// date add
elseif (preg_match('#^admin/dates/new$#', $q)) {
    define('Action', 'newdate');

    do_header('Agregar fecha');
    include($CFG->admdir . 'date_edit.php');
}

// dates list
elseif (preg_match('#^admin/dates/?$#', $q)) {
    define('Action', 'listdates');

    do_header('Lista de fechas para eventos');
    include($CFG->admdir . 'date_list.php');
    do_submit_cancel('', 'Volver al Menu', get_url('admin'));
}

// date edit
elseif (preg_match('#^admin/dates/\d+/?$#', $q)) {
    define('Action', 'editdate');
    $return_url = get_url('admin/dates');

    do_header('Editar fecha');
    include($CFG->admdir . 'date_edit.php');
}

// date delete
elseif (preg_match('#^admin/dates/\d+/delete$#', $q)) {
    define('Action', 'deletedate');
    $return_url = get_url('admin/dates');

    do_header('Eliminar fecha');
    include($CFG->admdir . 'date_delete.php');
}

// date events
elseif (preg_match('#^admin/dates/\d+/events$#', $q)) {
    define('Action', 'eventsdate');
    $return_url = get_url('admin/dates');

    do_header('Eventos por fecha');

?> <h1>Lista de eventos por fecha</h1> <?php

    include($CFG->admdir . 'event_list.php');
    do_submit_cancel('', 'Regresar', $return_url);
}

/*
 * Events
 *
 */

// list proposals to attach events
elseif (preg_match('#^admin/events/new/?$#', $q)) {
    define('Action', 'newevent');
    $return_url = get_url('admin');
    $not_found_message = 'No se encontro ninguna ponencia habilitada o ya se encuentran programadas.';

    do_header('Listado de ponencias habilitadas');

?> <h1>Lista de ponencias listas para ser programadas</h1> <?php

    include($CFG->comdir . 'prop_list.php');
    do_submit_cancel('', 'Regresar', $return_url);
}

// add event
elseif (preg_match('#^admin/events/new/\d+?$#', $q)) {
    define('Action', 'newevent');
    $return_url = get_url('admin/events/new');

    do_header('Listado de ponencias habilitadas');
    include($CFG->admdir . 'event_edit.php');
}

// edit event
elseif (preg_match('#^admin/events/\d+/?$#', $q)) {
    define('Action', 'editevent');
    $return_url = get_url('admin/events');

    do_header('Reprogramar Evento');
    include($CFG->admdir . 'event_edit.php');
}

// events list
elseif (preg_match('#^admin/events/?$#', $q)) {
    define('Action', 'listevents');

    do_header('Lista de eventos');

?> <h1>Lista de eventos programados</h1> <?php

    include($CFG->admdir . 'event_list.php');
    do_submit_cancel('', 'Volver al Menu', get_url('admin'));
}


/*
 * Administration
 *
 */

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

// config manager
elseif (preg_match('#^admin/catalog/?$#', $q)) {
    define('Action', 'catalog');

    do_header('Administrar Catálogos del Sistema');
    include($CFG->admdir . 'catalog_manager.php');
}

// add new admin user
elseif (preg_match('#^admin/new$#', $q)) {
    define('Action', 'newadmin');

    do_header('Nuevo administrador');
    include($CFG->comdir . 'user_edit.php');
}

// list admin users
elseif (preg_match('#^admin/list$#', $q)) {
    define('Action', 'listadmins');

    do_header('Lista de administradores');
    include($CFG->admdir . 'admin_list.php');
}

// admin action, change user tadmin
elseif (preg_match('#^admin/\d+/type/\d+$#', $q)) {
    define('Action', 'editadmin');
    include($CFG->admdir . 'admin_action.php');
}

/*
 * Delete Users
 *
 */

// admin delete
elseif (preg_match('#^admin/\d+/delete$#', $q)) {
    define('Action', 'deleteadmin');
    do_header('Eliminar administrador');
//    include($CFG->admdir . 'admin_delete.php');
    include($CFG->admdir . 'user_delete.php');
}

// admin delete
elseif (preg_match('#^admin/speakers/\d+/delete$#', $q)) {
    define('Action', 'deletespeaker');
    do_header('Eliminar ponente');
//    include($CFG->admdir . 'admin_delete.php');
    include($CFG->admdir . 'user_delete.php');
}

// admin delete
elseif (preg_match('#^admin/persons/\d+/delete$#', $q)) {
    define('Action', 'deleteperson');
    do_header('Eliminar asistente');
//    include($CFG->admdir . 'admin_delete.php');
    include($CFG->admdir . 'user_delete.php');
}

// menu edit details
elseif (preg_match('#^admin/details$#', $q)) {
    define('Action', 'editdetails');

    do_header('Modificar información personal');
    include($CFG->comdir . 'user_edit.php');
}

// page not found
else {
    do_header('Página no encontrada');
    include($CFG->tpldir . 'error_404.tmpl.php');
    do_submit_cancel('', 'Regresar');
}

// footer is called in main index
?>
