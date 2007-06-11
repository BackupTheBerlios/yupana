<?php
// die or init user session
if (empty($CFG)) {
    die;
}

switch (Context) {
    case 'admin':
        beginSession('R');
        $sess_login = $_SESSION['YACOMASVARS']['rootlogin'];
        break;

    case 'ponente':
        beginSession('P');
        $sess_login = $_SESSION['YACOMASVARS']['ponlogin'];
        break;

    case 'asistente':
        beginSession('A');
        $sess_login = $_SESSION['YACOMASVARS']['asilogin'];
        break;

    default:
        die; // if unknown context
}

$logout_url = $CFG->wwwroot . '/logout.php?context=' . Context;

do_header();
?>

<div id="login-info">
    <p class="yacomas_login">Usuario: <?=$sess_login ?> | <a class="precaucion" href="<?=$logout_url ?>">Cerrar Sesión</a>
    </p>
</div>
