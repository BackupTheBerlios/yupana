<?php
if (empty($CFG)) {
    die;
}

switch (Context) {
    case 'admin':
        $t = 'R';
        $name = 'Administrador';
        break;

    case 'ponente':
        $t = 'P';
        $name = 'Ponente';
        break;

    case 'asistente':
        $t = 'A';
        $name = 'Asistente';
        break;

    default:
        // force session destroy
        header('Location: ' . get_url('logout'));
}

beginSession($t);

// ignore erros
@session_unset();
@session_destroy();

//start page
do_header();
?>

<h1>Salida de Sesión <?=$name ?></h1>
<div class="block"></div>

<p class="center">Ha salido exitosamente del sistema.</p>

<?php
do_submit_cancel('', 'Continuar', get_url());
?>
