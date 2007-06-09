<?php
    require_once('../includes/lib.php');

    $option = optional_param('opc');

    switch ($option) {
        case NASISTENTE:
            include "Nasistente.php";
            break;

        default:
			include "signin.php";
    }
?>
