<?php
    // die or init user session
    if (empty($CFG)) {
        die;
    }

    switch (Context) {
        case 'admin':
            beginSession('R');
            $sess_login = $_SESSION['YACOMASVARS']['rootlogin'];
            $logout_url = $CFG->wwwroot . '/admin/signout.php';
            break;

        case 'ponente':
            beginSession('P');
            $sess_login = $_SESSION['YACOMASVARS']['ponlogin'];
            $logout_url = $CFG->wwwroot . '/ponente/signout.php';
            break;

        case 'asistente':
            beginSession('A');
            $sess_login = $_SESSION['YACOMASVARS']['asilogin'];
            $logout_url = $CFG->wwwroot . '/asistente/signout.php';
            break;

        default:
            die; // if unknown context
    }

    do_header();
?>

<div id="login-info">
    <p class="yacomas_login">Usuario: <?=$sess_login ?> | <a class="precaucion" href="<?=$logout_url ?>">Cerrar Sesión</a>
    </p>
</div>
