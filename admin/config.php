<?php 
// configuration manager
// just switch on/off conf flags
require_once('header-common.php');

$rootlevel = $_SESSION['YACOMASVARS']['rootlevel'];
?>

<h1>Configuración Yacomas</h1>

<?php
if ($conf_values=get_records('config')) {

    $table_data = array();
    $table_data[] = array('Nombre', 'Estado', 'Acción');

    foreach($conf_values as $conf) {
        $status_desc = ($conf->status) ? 'Abierto' : 'Cerrado';
        // toggle status
        $status_toggle = ($conf->status) ? 0 : 1;

        $action_desc = ($conf->status) ? 'Cerrar' : 'Abrir';
        $action_class = ($conf->status) ? 'precaucion' : 'verde';

        $action = <<< END
<a class="{$action_class}" href="act_conf.php?vconf={$conf->id} {$status_toggle}">{$action_desc}</a>
END;

        $table_data[] = array($conf->descr, $status_desc, $action);
    }

    do_table($table_data, 'narrow');

    // show system config values
    if ($rootlevel == 1) {

        $configs = array(
            'conference_name',
            'conference_link',
            'adminmail',
            'general_mail',
            'wwwroot',
            'limite',
            'def_hora_ini',
            'def_hora_fin',
            'send_mail',
            'smtp'
            );

        foreach ($configs as $config) {
            $value = optional_param($config);
            if (!empty($value)) {
                set_config ($config, $value);
            }
        }
        
?>

<h1>Valores del Sistema</h1>

<form method="POST" action="">

<?php
        $config = get_config('conference_name');
        $conference_name = <<< END
<input name="{$config->name}" type="text" size="30" value="{$config->value}" />
END;

        $config = get_config('conference_link');
        $conference_link = <<< END
<input name="{$config->name}" type="text" size="30" value="{$config->value}" />
END;

        $config = get_config('adminmail');
        $adminmail = <<< END
<input name="{$config->name}" type="text" size="30" value="{$config->value}" />
END;

        $config = get_config('general_mail');
        $general_mail = <<< END
<input name="{$config->name}" type="text" size="30" value="{$config->value}" />
END;

        $config = get_config('wwwroot');
        if (empty($config)) {
            $config = new StdClass;
            $config->name = 'wwwroot';
            $config->value = $CFG->wwwroot;
        }
        $wwwroot = <<< END
<input name="{$config->name}" type="text" size="30" value="{$config->value}" />
END;

        $config = get_config('limite');
        $limite = <<< END
<input name="{$config->name}" type="text" size="3" value="{$config->value}" />
END;

        $config = get_config('def_hora_ini');
        $hora_inicio = "<select name=\"{$config->name}\">";
        for ($h=1; $h<=24; $h++) {
            $hora = sprintf('%02d', $h);
            $selected = ($config->value == $hora) ? 'selected="selected"' : '';
            $hora_inicio .= "<option value=\"{$hora}\" {$selected}>{$hora}</option>";
        }
        $hora_inicio .= "</select>";

        $config = get_config('def_hora_fin');
        $hora_fin = "<select name=\"{$config->name}\">";
        for ($h=1; $h<=24; $h++) {
            $hora = sprintf('%02d', $h);
            $selected = ($config->value == $hora) ? 'selected="selected"' : '';
            $hora_fin .= "<option value=\"{$hora}\" {$selected}>{$hora}</option>";
        }
        $hora_fin .= "</select>";

        $config = get_config('send_mail');
        $send_mail = "<select name=\"{$config->name}\">";
        $selected = (empty($config->value)) ? 'selected="selected"' : '';
        $send_mail .= "<option value=\"{$config->value}\" {$selected}>No</option>";
        $selected = (!empty($config->value)) ? 'selected="selected"' : '';
        $send_mail .= "<option value=\"{$config->value}\" {$selected}>Si</option>";
        $send_mail .= "</select>";

        $config = get_config('smtp');
        $smtp = <<< END
<input name="{$config->name}" type="text" size="30" value="{$config->value}" />
END;

        $values = array(
            'Nombre del Evento' => $conference_name,
            'URL del Evento' => $conference_link,
            'Email de Contacto' => $adminmail,
            'Email automático' => $general_mail,
            'URL del Sistema' => $wwwroot,
            'Número máximo asistentes a Tutoriales y/o Talleres' => $limite,
            'Hora de inicio general' => $hora_inicio,
            'Hora de termino general' => $hora_fin,
            'Enviar mensajes por email?' => $send_mail,
            'Servidor SMTP' => $smtp
            );

        do_table_values($values, 'narrow');
?>

    <p class="center">
        <input type="submit" name="submit" value="Guardar" />
    </p>
</form>

<?php
    }
} else {
?>

<h2 class="error center">No se pudo obtener los valores de configuración. Consulte a su administrador</h2>

<?php } ?>
    
<p id="buttons">
    <input type="button" value="Volver al menu" onClick="location.href='<?=$CFG->wwwroot ?>/admin/menuadmin.php'" />
</p>

<?php
do_footer();
?>
