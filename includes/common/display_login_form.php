<?php
    if (empty($CFG)) {
        die;
    }
    
    // Common login form
    $table_data = array();

    $table_data[] = array(
        'Nombre de Usuario: ',
        do_get_output('do_input', array('S_login', 'text', $login, 'size="15"'))
        );

    $table_data[] = array(
        'Contraseña: ',
        do_get_output('do_input', array('S_passwd', 'password', '', 'size="15"'))
        );
?>

<form method="POST" action="">

<?php do_table_input($table_data); ?>

    <p class="notice center">Las cookies deben estar habilitadas para pasar este punto.<br />
    Su sesión caudará despues de 1 hora de inactividad.</p>

<?php do_submit_cancel('Iniciar', 'Cancelar', get_url()); ?>

</form>
