<?php
require_once('includes/lib.php');

$q = optional_param('q');

// view some proposal
if (preg_match('#^proposals/view/.+#', $q)) {

    do_header('Detalles de propuesta');
    include($CFG->incdir . 'common/prop_view.php');
    do_submit_cancel('', 'Regresar', $return_url);

// list proposals
} elseif (preg_match('#^proposals/list$#', $q)) {

    do_header('Lista de propuestas enviadas');
?>  <h1>Lista de propuestas enviadas</h1> <?php
    include($CFG->incdir . 'common/prop_list.php');
    do_submit_cancel('', 'Regresar', $CFG->wwwroot);

// view info of kind of proposals
} elseif (preg_match('#^proposals/info$#', $q)) {

    do_header('Modalidades de participación');
    include($CFG->rootdir . 'template/proposals_info.tmpl.php');
    // no need of back button

// default, show main index
} else {
    
    do_header();
    include($CFG->rootdir . 'template/main_index.tmpl.php');
}

// finally
do_footer();
?>
