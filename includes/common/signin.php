<?php
    // check
    if (empty($CFG)) {
        die;
    }

    // check correct values
    $is_admin_login = (empty($is_admin_login)) ? false : true;
    $is_asistente_login = (empty($is_asistente_login)) ? false : true;

    // submit vars
    $submit = optional_param('submit');
    $login = strtolower(optional_param('S_login'));
    $passwd = optional_param('S_passwd');
    $exp = optional_param('e');

    // type of login?
    if ($is_admin_login) {

        $sess_id = 'rootid';
        $table = 'administrador';
        $home_url = "{$CFG->wwwroot}/admin";
        $menu_url = "{$home_url}/menuadmin.php";

    } elseif ($is_asistente_login) {

        $sess_id = 'asiid';
        $table = 'asistente';
        $home_url = "{$CFG->wwwroot}/asistente";
        $menu_url = "{$home_url}/menuasistente.php";

    } else { 
        // duh?
        die;
    }

    // Check if use has session
    session_start();
    if (!empty($_SESSION['YACOMASVARS'][$sess_id]) && $exp != 'exp') {
        header("Location: {$menu_url}");
        exit; //no needed
    }

    if (!empty($submit)) {

        if (empty($passwd) || !preg_match("/^\w{4,15}$/", $login)) {
            $errmsg[] = "Usuario y/o contraseña no válidos. Por favor trate de nuevo.";
        } else {
            $user = get_record($table, 'login', $login);

            if (empty($user) || $user->passwd != md5($passwd)) {
                $errmsg[] = "Usuario y/o contraseña incorrectos. Por favor intente de nuevo o puede ingresar a <a href=\"{$home_url}/reset.php\">Recuperar Contraseña</a>";
            } else {
                // User ok, init session data
                @session_start(); // ignore errors
                session_register('YACOMASVARS');

                if ($is_admin_login) {
                    $_SESSION['YACOMASVARS']['rootid'] = $user->id;
                    $_SESSION['YACOMASVARS']['rootlogin'] = $user->login;
                    $_SESSION['YACOMASVARS']['rootlevel'] = $user->id_tadmin;
                    $_SESSION['YACOMASVARS']['rootlast'] = time();;
                } elseif ($is_asistente_login) {
                    $_SESSION['YACOMASVARS']['asiid'] = $user->id;
                    $_SESSION['YACOMASVARS']['asilogin'] = $user->login;
                    $_SESSION['YACOMASVARS']['asilast'] = time();;
                }

                // redirect to main menu
                header("Location: {$menu_url}");
                exit;
            }
        }
    }
?>
