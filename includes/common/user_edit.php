<?php
if (!defined('Context') || empty($CFG)
    || (Context != 'admin'
        && Context != 'ponente'
        && Context != 'asistente')) {

    header('Location: ' . get_url());
}

switch (Context) {
    case 'admin':
        if (Action == 'newadmin') {
            $title = 'Registro de Administrador';
        }
       
        elseif (Action == 'newspeaker') {
            $title = 'Registro de Ponente';
        }

        else {
            $title = 'Modificar datos de Administrador';
            $user = get_admin($USER->id);
        }

        break;

    case 'ponente':
        if (Action == 'register') {
            $title = 'Registro de Ponentes';
        } else {
            $title = 'Modificar datos de Ponente';
            $user = get_speaker($USER->id);
        }
        break;

    case 'asistente':
        if (Action == 'register') {
            $title = 'Registro de Asistentes';
        } else {
            $title = 'Modificar datos de Asistente';
            $user = get_person($USER->id);
        }
        break;
}

require($CFG->comdir . 'user_optional_params.php');

?> <h1><?=$title ?></h1> <?php

if (Action == 'register') {
    require($CFG->comdir . 'register_flag_check.php');
}

// process submit
if (!empty($submit)) {
    // messages holder
    $errmsg = array();

    require($CFG->comdir . 'user_optional_params_check.php');

    if (!empty($errmsg)) {
        // no show error message on first check of user on external auth
        if ($submit != 'Iniciar') {
            show_error($errmsg);
        }
    } else {
        // update user
        require($CFG->comdir . 'user_update_info.php');

        if (Action == 'register' || Action == 'newadmin') {
            $action_name = 'Continuar';

            if (Context == 'ponente') {

                $return_url = get_url('speaker/login');

            } elseif (Context == 'asistente') {

                $return_url = get_url('person/login');

            } elseif (Context == 'admin') {

                $action_name = 'Volver al Menu';
                $return_url = get_url('admin');

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
    include($CFG->comdir . 'user_input_table.php');

    if (Action == 'register') {
        $action_name = 'Registrarme';
    }

    elseif (Action == 'newadmin' || Action == 'newspeaker' || Action == 'newperson') {
        $action_name = 'Registrar';
    }

    else {
        $action_name = 'Actualizar';
    }

    do_submit_cancel($action_name, 'Cancelar', $return_url);
    ?>

</form>

<?php
}
?>
