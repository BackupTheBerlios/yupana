<?php

function get_info($where, $type='person', $limit='', $order='') {
    $record = new StdClass;

    // hide deleted proposals to non admins
    if ($type == 'proposal' && Context != 'admin') {
        $where .= ' AND P.id_status != 7';
    }

    if (!empty($limit) && $limit != 1) {
        $where .= ' LIMIT ' . $limit;
    }

    if (empty($order)) {
        //default order
        if ($type == 'proposal') {
            $where .= ' ORDER BY P.id, P.reg_time';
        } 
            
        elseif ($type == 'speaker') {
            $where .= ' ORDER BY SP.id, SP.reg_time';
        }
    } else {
        $where .= ' ORDER BY ' . $order;
    }

    // return admin details
    if ($type == 'admin') {
        $query = '
            SELECT ADM.*, T.descr AS tadmin, T.tareas
            FROM administrador ADM
            JOIN tadmin T ON ADM.id_tadmin = T.id
            WHERE ' . $where;
    }

    // return speaker details
    elseif ($type == 'speaker') {
        $query = '
            SELECT SP.*, E.descr AS estudios, ST.descr AS estado
            FROM ponente SP
            JOIN estudios E ON SP.id_estudios = E.id
            JOIN estado ST ON SP.id_estado = ST.id
            WHERE ' . $where;
    }

    // return person details
    elseif ($type == 'person') {
        $query = '
            SELECT P.*, E.descr AS estudios, ST.descr AS estado,
            T.descr AS tasistente
            FROM asistente P
            JOIN estudios E ON P.id_estudios = E.id
            JOIN estado ST ON P.id_estado = ST.id
            JOIN tasistente T ON P.id_tasistente = T.id
            WHERE ' . $where;
    }

    // proposals?
    elseif ($type == 'proposal') {
        $query = '
            SELECT P.*, L.descr AS nivel, SP.login, SP.nombrep,
            SP.apellidos, SP.org, SP.resume, T.descr AS tipo,
            ADM.mail AS adminmail, ADM.login AS adminlogin,
            O.descr AS orientacion, S.descr AS status
            FROM propuesta P
            JOIN prop_nivel L ON L.id = P.id_nivel
            JOIN ponente SP ON SP.id = P.id_ponente
            JOIN prop_tipo T ON T.id = P.id_prop_tipo
            LEFT JOIN administrador ADM ON ADM.id = P.id_administrador
            JOIN orientacion O ON O.id = P.id_orientacion
            JOIN prop_status S ON S.id = P.id_status
            WHERE ' . $where;
    }

    // get only one record?
    if ($limit == 1) {
        $records = get_record_sql($query);

        //get event if programmed 
        if (!empty($records) && $type == 'proposal' && $records->id_status == 8) {
            $query = '
                SELECT FE.fecha, R.nombre_lug AS lugar, EO.hora
                FROM evento E
                JOIN evento_ocupa EO ON EO.id_evento = E.id
                JOIN lugar R ON R.id = EO.id_lugar
                JOIN fecha_evento FE ON FE.id = EO.id_fecha
                WHERE E.id_propuesta=' . $records->id;

            $event = get_record_sql($query);
            $endhour = $event->hora + $records->duracion -1;

            $records->fecha = $event->fecha;
            $records->human_date = friendly_date($event->fecha);
            $records->lugar = $event->lugar;
            $records->hora = $event->hora;
            $records->time = sprintf('%02d:00 - %02d:50', $event->hora, $endhour);
        }

    } else {
        $records = get_records_sql($query);
    }

    return $records;
}

function get_admins($where='1=1', $limit='', $order='ADM.apellidos, ADM.nombrep, ADM.id') {
    return get_info($where, 'admin', $limit, $order);
}

function get_speakers($where='1=1', $limit='', $order='SP.apellidos, SP.nombrep, SP.id') {
    return get_info($where, 'speaker', $limit, $order);
}

function get_persons($where='1=1', $limit='', $order='P.apellidos, P.nombrep, P.id') {
    return get_info($where, 'person', $limit, $order);
}

function get_proposals($where='1=1', $limit='', $order='') {
    return get_info($where, 'proposal', $limit, $order);
}

function get_admin($id) {
    return get_info('ADM.id='.$id, 'admin', 1);
}

function get_speaker($id) {
    return get_info('SP.id='.$id, 'speaker', 1);
}

function get_person($id) {
    return get_info('P.id='.$id, 'person', 1);
}

function get_proposal($id, $id_speaker=0) {
    $where = 'P.id='.$id;

    if (!empty($id_speaker)) {
        $where .= ' AND P.id_ponente='.$id_speaker;
    }

    return get_info($where, 'proposal', 1);
}

function check_login($login, $pass, $type='person') {

    if ($type == 'admin') {
        $table = 'administrador';
    } elseif ($type == 'speaker') {
        $table = 'ponente';
    } elseif ($type == 'person') {
        $table = 'asistente';
    }

    return get_field($table, 'id', 'login', $login, 'password', md5($pass));
}

function friendly_date($date, $show_year=false) {
    // format 0000-00-00
    $ds = split('-', $date);

    $year = (int) $ds[0];
    $month = (int) $ds[1];
    $day = (int) $ds[2];

    if (!empty($year) && !empty($month) && !empty($day)) {
        $time = mktime(0, 0, 0, $month, $day, $year);

        if ($show_year) {
            $format = '%A %d de %B, %Y';
        } else {
            $format = '%A %d de %B';
        }

        $human_date = strftime_caste($format, $time);
    } else {
        $human_date = '';
    }

    return $human_date;
}
?>
