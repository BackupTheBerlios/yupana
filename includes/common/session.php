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
        $CFG->logout_url = $CFG->wwwroot . '/?q=admin/logout';
        break;

    case 'ponente':
        beginSession('P');
        $sess_id = $_SESSION['YACOMASVARS']['ponid'];
        $USER = get_record('ponente', 'id', $sess_id);
        $CFG->logout_url = $CFG->wwwroot . '/?q=speaker/logout';
        break;

    case 'asistente':
        beginSession('A');
        $sess_id = $_SESSION['YACOMASVARS']['asiid'];
        $USER = get_record('asistente', 'id', $sess_id);
        $CFG->logout_url = $CFG->wwwroot . '/?q=person/logout';
        break;

    default:
        die; // if unknown context
}
?>
