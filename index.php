<?php
require_once('includes/lib.php');

/*
 * Main routing views
 *
 */

$q = optional_param('q');

if (empty($q)) {
    // default index
    do_header();
    include($CFG->tpldir . 'main_index.tmpl.php');
}

/*
 * Register
 *
 */
// admin login
/*if (preg_match('#^admin/register$#', $q)) {

    define('Context', 'admin');
    include($CFG->comdir . 'user_edit.php');
}*/

// author register
elseif (preg_match('#^speaker/register$#', $q)) {

    define('Context', 'ponente');
    define('Action', 'register');

    // return url
    $return_url = get_url();

    do_header('Registro de Ponentes');
    include($CFG->comdir . 'user_edit.php');
}

// person register
elseif (preg_match('#^person/register$#', $q)) {

    define('Context', 'asistente');
    define('Action', 'register');

    // return url
    $return_url = get_url();

    do_header('Registro de Asistentes');
    include($CFG->comdir . 'user_edit.php');
}

/*
 * Recover user password or login
 *
 */

// 
/*elseif (preg_match('#^admin/recover$#', $q)) {

    define('Context', 'admin');
    define('Action', 'recover')
    include($CFG->comdir . 'do_login.php');
}*/

/*
 * Login 
 *
 */

// admin login
elseif (preg_match('#^admin/login$#', $q)) {

    define('Context', 'admin');
    include($CFG->comdir . 'do_login.php');
}

// author login
elseif (preg_match('#^speaker/login$#', $q)) {

    define('Context', 'ponente');
    include($CFG->comdir . 'do_login.php');
}

// person login
elseif (preg_match('#^person/login$#', $q)) {

    define('Context', 'asistente');
    include($CFG->comdir . 'do_login.php');
}

/*
 * logout
 *
 */

// admin login
elseif (preg_match('#^admin/logout$#', $q)) {

    define('Context', 'admin');
    include($CFG->comdir . 'do_logout.php');
}

// author login
elseif (preg_match('#^speaker/logout$#', $q)) {

    define('Context', 'ponente');
    include($CFG->comdir . 'do_logout.php');
}

// person login
elseif (preg_match('#^person/logout$#', $q)) {

    define('Context', 'asistente');
    include($CFG->comdir . 'do_logout.php');
}

// force session destroy
elseif (preg_match('#^logout$#', $q)) {
    //ignore errors
    @session_start();
    @session_unset();
    @session_destroy();

    do_header();
?>

<h1>Sesión Terminada</h1>
<div class="block"></div>

<p class="error center">Tu sesión ha caducado o salido forzosamente.</p>

<?php
    do_submit_cancel('', 'Continuar', get_url());
}

/*
 * Not logged in views
 *
 */

// list proposals
elseif (preg_match('#^general/proposals/?$#', $q)) {
    define('Context', 'main');
    define('Action', 'viewproposal');

    do_header('Lista de propuestas enviadas');

?>  <h1>Lista de propuestas enviadas</h1> <?php

    include($CFG->comdir . 'prop_list.php');
    do_submit_cancel('', 'Regresar', get_url());
}

// view some proposal
elseif (preg_match('#^general/proposals/.+#', $q)) {
    define('Context', 'main');
    define('Action', 'listproposals');

    do_header('Detalles de propuesta');
    include($CFG->comdir . 'prop_view.php');
    do_submit_cancel('', 'Regresar', get_url('general/proposals'));
}

// view author resume
elseif (preg_match('#^general/authors/.+#', $q)) {

    define('Context', 'main');
    do_header('Detalles de autor');
    include($CFG->comdir . 'author_view.php');
    do_submit_cancel('', 'Regresar', get_url('general/proposals'));
}

// view info of kind of proposals
elseif (preg_match('#^general/information$#', $q)) {

    do_header('Modalidades de participación');
    include($CFG->tpldir . 'proposals_info.tmpl.php');
    do_submit_cancel('', 'Regresar', get_url());
}

/*
 * schedule views
 *
 */

//TODO

/*
 * admin views
 *
 */
elseif (preg_match('#^admin/*+#', $q)) {

    // Delegate routing
    include($CFG->incdir . 'admin_views.php');
}
 
/*
 *  author views
 *
 */
elseif (preg_match('#^speaker/*+#', $q)) {

    // Delegate routing
    include($CFG->incdir . 'speaker_views.php');
}
 
/*
 *  person views
 *
 */
elseif (preg_match('#^person/*+#', $q)) {

    // Delegate routing
    include($CFG->incdir . 'person_views.php');
}

/*
 * Default index
 *
 */
else {
    do_header('Página no encontrada');
    include($CFG->tpldir . 'error_404.tmpl.php');
    do_submit_cancel('', 'Volver');
}

// finally
do_footer();
?>
