<?php
// called directly?
if (empty($CFG)) {
    header('Location: ../?q=author');
}

// Globals
define('Context', 'ponente');
$home_url = $CFG->wwwroot . '/?q=author';

// init session
require($CFG->incdir . 'common/session.php');

$q= optional_param('q');

// default index
if (preg_match('#^author/?$#', $q)) {
    // default index
    do_header('Menu Ponentes');
    include($CFG->rootdir . 'template/author_menu.tmpl.php');
}

/*
 * Routing
 *
 */

// menu edit details
elseif (preg_match('#^author/details$#', $q)) {
    do_header('Modificar información personal');
    include($CFG->incdir . 'common/user_edit.php');
}

// menu proposals list
elseif (preg_match('#^author/proposals/?$#', $q)) {
    do_header('Lista de propuestas enviadas');

?>  <h1>Lista de ponencias enviadas</h1> <?php

    include($CFG->incdir . 'common/prop_list.php');
    do_submit_cancel('', 'Regresar', $home_url);
}

// menu add proposals
elseif (preg_match('#^author/proposals/new$#', $q)) {
    define('Action', 'newproposal');
    do_header('Enviar propuesta de ponencia');
    include($CFG->incdir . 'common/prop_edit.php');
}

// view proposals details
elseif (preg_match('#^author/proposals/\d+/?$#', $q)) {
    do_header('Detalles de propuesta');
    include($CFG->incdir . 'common/prop_view.php');
    do_submit_cancel('', 'Regresar', $home_url.'/proposals');
}

// edit proposals details
elseif (preg_match('#^author/proposals/\d+/update$#', $q)) {
    define('Action', 'updateproposal');
    do_header('Modificar propuesta');
    $home_url = $home_url.'/proposals'; // back to proposals list
    include($CFG->incdir . 'common/prop_edit.php');
}

// delete proposal
elseif (preg_match('#^author/proposals/(\d+)/delete$#', $q)) {
    do_header('Eliminar propuesta de ponencia');
    $home_url = $home_url . '/proposals';
    include($CFG->incdir . 'common/prop_delete.php');
}

// page not found
else {
    include($CFG->rootdir . 'template/error_404.tmpl.php');
}

// footer is called in main index
?>
