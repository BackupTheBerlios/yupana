<?php
    // Update admin level

    require_once('../includes/lib.php');

    beginSession('R');

    $admin_id = optional_param('admin', 0, PARAM_INT);
    $id_tadmin = optional_param('level', 0);
    $return_path = optional_param('return_path');

    if (!empty($id_tadmin)) {
        $admin = get_record_select('administrador', 'id=?', array($admin_id));

        // only change level if it's diferent
        if (!empty($admin) && $admin->id_tadmin != $id_tadmin) {
            $admin->id_tadmin = $id_tadmin;
            update_record('administrador', $admin);
        }
    }

    header('Location: ' . $return_path);

?>
