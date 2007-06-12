<?php
require_once('includes/lib.php');

/*
 * Main routing views
 *
 */

$q = optional_param('q');

/*
 * Register
 *
 */

// admin login
/*if (preg_match('#^admin/register$#', $q)) {

    define('Context', 'admin');
    include($CFG->incdir . 'common/user_edit.php');

// author login
} else*/
if (preg_match('#^author/register$#', $q)) {

    define('Context', 'ponente');
    define('Register', true);

    // return url
    $home_url = $CFG->wwwroot;

    do_header('Registro de Ponentes');
    include($CFG->incdir . 'common/user_edit.php');

// person login
} elseif (preg_match('#^person/register$#', $q)) {

    define('Context', 'asistente');
    define('Register', true);

    // return url
    $home_url = $CFG->wwwroot;

    do_header('Registro de Asistentes');
    include($CFG->incdir . 'common/user_edit.php');

/*
 * Login 
 *
 */

// admin login
} elseif (preg_match('#^admin/login$#', $q)) {

    define('Context', 'admin');
    include($CFG->incdir . 'common/do_login.php');

// author login
} elseif (preg_match('#^author/login$#', $q)) {

    define('Context', 'ponente');
    include($CFG->incdir . 'common/do_login.php');

// person login
} elseif (preg_match('#^person/login$#', $q)) {

    define('Context', 'asistente');
    include($CFG->incdir . 'common/do_login.php');

/*
 * logout
 *
 */

// admin login
} elseif (preg_match('#^admin/logout$#', $q)) {

    define('Context', 'admin');
    include($CFG->incdir . 'common/do_logout.php');

// author login
} elseif (preg_match('#^author/logout$#', $q)) {

    define('Context', 'ponente');
    include($CFG->incdir . 'common/do_logout.php');

// person login
} elseif (preg_match('#^person/logout$#', $q)) {

    define('Context', 'asistente');
    include($CFG->incdir . 'common/do_logout.php');

/*
 * Not logged in views
 *
 */

// view some proposal
} elseif (preg_match('#^general/proposals/.+#', $q)) {

    define('Context', 'main');
    do_header('Detalles de propuesta');
    include($CFG->incdir . 'common/prop_view.php');
    do_submit_cancel('', 'Regresar', $return_url);

// view author resume
} elseif (preg_match('#^general/authors/.+#', $q)) {

    define('Context', 'main');
    do_header('Detalles de autor');
    include($CFG->incdir . 'common/author_view.php');
    do_submit_cancel('', 'Regresar', $return_url);

// list proposals
} elseif (preg_match('#^general/proposals$#', $q)) {

    define('Context', 'main');
    do_header('Lista de propuestas enviadas');
?>  <h1>Lista de propuestas enviadas</h1> <?php
    include($CFG->incdir . 'common/prop_list.php');
    do_submit_cancel('', 'Regresar', $CFG->wwwroot);

// view info of kind of proposals
} elseif (preg_match('#^general/informartion$#', $q)) {

    do_header('Modalidades de participación');
    include($CFG->rootdir . 'template/proposals_info.tmpl.php');
    // no need of back button

/*
 * Default index
 *
 */

} else {
    
    do_header();
    include($CFG->rootdir . 'template/main_index.tmpl.php');
}

// finally
do_footer();
?>
