<?php
// die or init user session
if (empty($CFG)) {
    die;
}

// current session user info
global $USER;

switch (Context) {
    case 'admin':
        beginSession('R');
        $sess_id = $_SESSION['YACOMASVARS']['rootid'];
        $USER = get_record('administrador', 'id', $sess_id);
        break;

    case 'ponente':
        beginSession('P');
        $sess_id = $_SESSION['YACOMASVARS']['ponid'];
        $USER = get_record('ponente', 'id', $sess_id);
        break;

    case 'asistente':
        beginSession('A');
        $sess_id = $_SESSION['YACOMASVARS']['asiid'];
        $USER = get_record('asistente', 'id', $sess_id);
        break;

    default:
        die; // if unknown context
}

$logout_url = $CFG->wwwroot . '/logout.php?context=' . Context;

do_header();
?>

<div id="login-info">
    <p class="yacomas_login">Usuario: <?=$USER->login ?> | <a class="precaucion" href="<?=$logout_url ?>">Cerrar Sesión</a>
    </p>
</div>
