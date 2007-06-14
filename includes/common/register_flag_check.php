<?php
    // die
    if (empty($CFG)) {
        die;
    }

    if (Context == 'ponente') {
        $id = 1; // REGPONENTES
        $name = 'ponentes';
    } elseif (Context == 'asistente') {
        $id = 2; // REGASISTENTES
        $name = 'asistentes';
    } else {
        die; //FIXME
    }

    // Check if register is open 
    $open_flag = get_field('config', 'status', 'id', $id);

    // if not open, end page
    if (!$open_flag && Context != 'admin') {
?>

<div class="block"></div>

<p class="error center">El registro de <?=$name ?> se encuentra cerrado. Gracias por tu interes</p>

<?php
        do_submit_cancel('', 'Continuar', $return_url);
        do_footer();
        exit;
    }
