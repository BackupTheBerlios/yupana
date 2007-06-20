<?php
    // running from system?
    if (empty($CFG) || empty($proposal)) {
        die;
    }
   
    $files = get_records('prop_files', 'id_propuesta', $proposal->id);

    if (empty($files)) {
?>

<p class="error center">Esta propuesta no tiene archivos adjuntos</p>

<?php
    } else {
        
        $table_data = array();
        // headers
        $table_data[] = array('Nombre', 'Descripción', 'Tamaño', 'Público', '');

        foreach ($files as $f) {
            $public = (empty($f->public)) ? 'No' : 'Si';

            //download file
            $url = get_url('speaker/proposals/'.$proposal->id.'/files/'.$f->id.'/'.$f->name);
            $l_name = <<< END
<a href={$url}>{$f->title}</a>
END;

            //size 
            $size = sprintf('<span class="right">%s</span>', human_filesize($f->size));

            $url = get_url('speaker/proposals/'.$proposal->id.'/files/edit/'.$f->id.'/'.$f->name);
            $l_modify = <<< END
<a class="verde" href="{$url}">Modificar</a>
END;

            if ($proposal->id_status < 5) {
                $url = get_url('speaker/proposals/'.$proposal->id.'/files/delete/'.$f->id.'/'.$f->name);
                $l_delete = <<< END
&nbsp;|&nbsp;<a class="precaucion" href="{$url}">Eliminar</a>
END;
            } else {
                $l_delete = '';
            }

            $table_data[] = array(
                $l_name,
                $f->descr,
                $size,
                $public,
                $l_modify.$l_delete
                );
        }

        do_table($table_data, 'narrow-form files');
    }

?>
