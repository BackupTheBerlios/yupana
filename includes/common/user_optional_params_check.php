<?php
    // halt if running directly
    if (empty($CFG)) {
        die;
    }

    // set database table
    if (Context == 'admin') {
        $dbtable = 'administrador';

        // actualizar
        if (!empty($idadmin)) {
            $userid = $idadmin;
        }
 
    }

    if (Context == 'ponente') {
        $dbtable = 'ponente';

        // actualizar
        if (!empty($idponente)) {
            $userid = $idponente;
        }
    } 
    
    if (Context == 'asistente') {
        $dbtable = 'asistente';

        // actualizar
        if (!empty($idasistente)) {
            $userid = $idasistente;
        }
    }

    // check submit value
    if (!empty($submit)
        && (Context == 'admin ' || Context == 'ponente' || Context == 'asistente')) {
        // Verificar si todos los campos obligatorios no estan vacios
        if (empty($login)
            || empty($nombrep)
            || empty($apellidos)
            || (Context != 'admin' && empty($sexo))
            || (Context != 'admin' && empty($id_estudios))
            || (Context != 'admin' && empty($id_estado))
            || (Context == 'admin' && $submit == 'Registrar' && empty($id_tadmin))
            || (Context == 'asistente' && empty($id_tasistente))) { 

            $errmsg[] = "Verifica que los datos obligatorios los hayas introducido correctamente.";
        }

        if (!preg_match("/.+\@.+\..+/",$mail)) {
            $errmsg[] = "El correo electrónico no es válido";
        }

        // Verifica que el login sea de al menos 4 caracteres
        if (!preg_match("/^\w{4,15}$/",$login)) {
            $errmsg[] = "El login que elijas debe tener entre 4 y 15 caracteres.";
        }

        if ($submit == 'Registrarme' || ($submit == 'Actualizar' && !empty($passwd))) {

            // Verifica que el password sea de al menos 6 caracteres
            if (!preg_match("/^.{6,15}$/",$passwd)) {
                $errmsg[] = "El password debe tener entre 6 y 15 caracteres.";
            }

            // Verifica que el password usado no sea igual al login introducido por seguridad
            elseif ($passwd == $login) {
                $errmsg[] = "El password no debe ser igual a tu login.";
            }

            // Verifica que los password esten escritos correctamente para verificar que
            // la persona introducjo correcamente el password que eligio.
            if ($passwd != $passwd2) {
                $errmsg[] = "Los passwords no concuerdan.";
            }
        }

        // Si no hay errores verifica que el login no este ya dado de alta en la tabla
        if (empty($errmsg)) {
            $user = get_record($dbtable, 'login', $login);

            if (!(empty($user))
                && ($submit == 'Registrarme'
                    || ($submit == 'Actualizar' 
                        && $user->id != $USER->id))) {
                $errmsg[] = 'El usuario que elegiste ya ha sido tomado; por favor elige otro';
            }
        }
    }

?>
