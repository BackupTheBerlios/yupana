<?php
    // halt if running directly
    if (empty($CFG)) {
        die;
    }

    // check submit value
    if (!empty($submit)
        && (Context == 'admin ' || Context == 'ponente')) {
        // Verificar si todos los campos obligatorios no estan vacios
        if ((Context == 'admin' && empty($login))
            || empty($nombreponencia)
            || empty($id_orientacion)
            || (defined('Action') && Action == 'newproposal' && empty($id_nivel))
            || empty($id_tipo)
            || empty($duracion)
            || empty($resumen)) { 

            $errmsg[] = "Verifica que los datos obligatorios los hayas introducido correctamente.";
        }

        if (Context == 'admin') {
            $idponente = get_field('ponente', 'login', $login);

            if (empty($idponente)) {
                $errmsg[] = 'El ponente que elegiste no existe. Por favor elige otro.';
            } 
        } else {
            $idponente = $USER->id;
        }

        if ($duracion > 2 && $id_tipo < 50) {
            $errmsg[] = 'Sólo talleres o tutoriales pueden tener durar más de 2 horas';
        }

        if (empty($errmsg)) {
//            $record = get_record('propuesta', 'nombre', $nombreponencia, 'id_ponente', $idponente);
            $record = get_record('propuesta', 'nombre', $nombreponencia);

            if (!empty($record)) {

                if ((defined('Action') && Action == 'newproposal')
                    || ($record->id_ponente != $USER->id && Context != 'admin')) {

                    $errmsg[] = 'El nombre de la ponencia ya ha sido dado de alta.';

                } else {
                    // record not empty and submit == update and user is admin or 
                    // user is owner
                    // set id for proposals 
                    $idponencia = $record->id; 
                }
            }
        }

    }
?>
