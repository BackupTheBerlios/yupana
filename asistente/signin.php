<?php
    require_once('../includes/lib.php');

    $is_asistente_login = true;
    $errmsg = array();

    require('../includes/common/signin.php');

    do_header();
    // show form
?>

<h1>Inicio de Sesión Asistente</h1>

<?php
    if (!empty($errmsg)) {

        show_error($errmsg);

    } elseif ($exp == "exp") { 
?>

<p class="error center">Su session ha caducado o no inicio session correctamente.  Por favor trate de nuevo.</p>

<?php } ?>

<form method="POST" action="">

<?php
$table_data = array();

$table_data[] = array(
    'Nombre de Usuario: ',
    do_get_output('do_input', array('S_login', 'text', $login, 'size="15"'))
    );

$table_data[] = array(
    'Contraseña: ',
    do_get_output('do_input', array('S_passwd', 'password', '', 'size="15"'))
    );

do_table_input($table_data);

do_submit_cancel('Iniciar', 'Cancelar', $CFG->wwwroot);

do_footer();
?>
