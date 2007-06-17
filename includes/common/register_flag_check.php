<?php
    // die
    if (empty($CFG)) {
        die;
    }

    if (Context == 'ponente' && Action == 'register') {
        $id = 1; // REGPONENTES
        $name = 'ponentes';
    } elseif (Context == 'asistente' && Action == 'register') {
        $id = 2; // REGASISTENTES
        $name = 'asistentes';
    } elseif (Context == 'ponente' && Action == 'newproposal') {
        $id = 3; // REGPONENCIAS
        $name = 'ponencias';
    } elseif (Context == 'asistente' && Action == 'workshopregister') {
        $id = 4; // INSCRIP
        $name = 'talleres/tutoriales';
    } else {
        if (Context == 'admin') {
            $open_flag = true;
        } else {
            die; // this never should be happen
        }
    }

    if (Context != 'admin') {
        // Check if register is open 
        $open_flag = get_field('config', 'status', 'id', $id);
    }

    // if not open, end page
    if (!$open_flag) {
?>

<div class="block"></div>

<p class="error center">El registro de <?=$name ?> se encuentra cerrado. Gracias por tu interes</p>

<?php
        do_submit_cancel('', 'Continuar', $return_url);
        do_footer();
        exit;
    }
?>
