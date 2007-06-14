<?php
if (!defined('Context') || empty($CFG)
    || (Context != 'admin'
        && Context != 'ponente'
        && Context != 'asistente')) {

    header('Location: ' . $CFG->wwwroot);
}

switch (Context) {
    case 'admin':
        if (Action == 'register') {
            $title = 'Registro de Administrador';
        } else {
            $title = 'Modificar datos de Administrador';
        }
        break;

    case 'ponente':
        if (Action == 'register') {
            $title = 'Registro de Ponentes';
        } else {
            $title = 'Modificar datos de Ponente';
        }
        break;

    case 'asistente':
        if (Action == 'register') {
            $title = 'Registro de Asistentes';
        } else {
            $title = 'Modificar datos de Asistente';
        }
        break;
}

require($CFG->incdir . 'common/user_optional_params.php');

?> <h1><?=$title ?></h1> <?php

if (Action == 'register') {
    require($CFG->incdir . 'common/register_flag_check.php');
}

// process submit
if (!empty($submit)) {
    // messages holder
    $errmsg = array();

    require($CFG->incdir . 'common/user_optional_params_check.php');

    if (!empty($errmsg)) {
        show_error($errmsg);
    } else {
        // update user
        require($CFG->incdir . 'common/user_update_info.php');

        if (Action == 'register') {
            $action_name = 'Continuar';

            if (Context == 'ponente') {
                $return_url = $CFG->wwwroot . '/?q=author/login';
            } elseif (Context == 'asistente') {
                $return_url = $CFG->wwwroot . '/?q=person/login';
            }

        } else {
            $action_name = 'Volver al Menu';
        }

        do_submit_cancel('', $action_name, $return_url);
    }
}

if (empty($submit) || !empty($errmsg)) { // show form
?> 

<form method="POST" action="">

    <?php if (Context == 'ponente' || Context == 'asistente') { ?>

    <p class="error">Asegúrate de escribir bien tus datos ya que estos serán
    tomados para tu constancia de participación.

        <?php if (Action != 'register') { ?>

    Deja la contraseña vacía para no cambiarla.

        <?php } ?>

    </p>

    <?php } ?>

    <p class="center"><em>Los campos marcados con asterisco(*) son obligatorios</em></p>

    <?php
    include($CFG->incdir . 'common/user_display_input_table.php');

    if (Action == 'register') {
        $action_name = 'Registrarme';
    } else {
        $action_name = 'Actualizar';
    }

    do_submit_cancel($action_name, 'Cancelar', $return_url);
    ?>

</form>

<?php
}
?>
