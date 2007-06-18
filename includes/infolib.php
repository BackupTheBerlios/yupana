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
            LEFT JOIN tadmin T ON ADM.id_tadmin = T.id
            WHERE ' . $where;
    }

    // return speaker details
    elseif ($type == 'speaker') {
        $query = '
            SELECT SP.*, E.descr AS estudios, ST.descr AS estado
            FROM ponente SP
            LEFT JOIN estudios E ON SP.id_estudios = E.id
            LEFT JOIN estado ST ON SP.id_estado = ST.id
            WHERE ' . $where;
    }

    // return person details
    elseif ($type == 'person') {
        $query = '
            SELECT P.*, E.descr AS estudios, ST.descr AS estado,
            T.descr AS tasistente
            FROM asistente P
            LEFT JOIN estudios E ON P.id_estudios = E.id
            LEFT JOIN estado ST ON P.id_estado = ST.id
            LEFT JOIN tasistente T ON P.id_tasistente = T.id
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
            LEFT JOIN prop_nivel L ON L.id = P.id_nivel
            LEFT JOIN ponente SP ON SP.id = P.id_ponente
            LEFT JOIN prop_tipo T ON T.id = P.id_prop_tipo
            LEFT JOIN administrador ADM ON ADM.id = P.id_administrador
            LEFT JOIN orientacion O ON O.id = P.id_orientacion
            LEFT JOIN prop_status S ON S.id = P.id_status
            WHERE ' . $where;
    }

    elseif ($type == 'event') {
        $query = '
            SELECT P.*, SP.nombrep, SP.apellidos,
            PT.descr AS tipo, O.descr AS orientacion,
            L.cupo, L.nombre_lug AS lugar, L.ubicacion,
            FE.fecha, FE.descr AS date_desc,
            EO.hora, EO.id_evento
            FROM evento E
            LEFT JOIN propuesta P ON P.id = E.id_propuesta
            LEFT JOIN ponente SP ON SP.id = P.id_ponente
            LEFT JOIN prop_tipo PT ON PT.id = P.id_prop_tipo
            LEFT JOIN orientacion O ON O.id = P.id_orientacion
            LEFT JOIN evento_ocupa EO ON EO.id_evento = E.id
            LEFT JOIN lugar L ON L.id = EO.id_lugar
            LEFT JOIN fecha_evento FE ON FE.id = EO.id_fecha
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
                LEFT JOIN evento_ocupa EO ON EO.id_evento = E.id
                LEFT JOIN lugar R ON R.id = EO.id_lugar
                LEFT JOIN fecha_evento FE ON FE.id = EO.id_fecha
                WHERE E.id_propuesta=' . $records->id . ' GROUP BY EO.id_evento';

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

function get_events($date_id=0, $room_id=0, $date='', $user_id=0) {
    // where, safe value
    $where = '1=1';

    // default order
    $order = 'FE.fecha,EO.hora';

    // date_id precedence
    if (!empty($date_id)) {
        $where .= ' AND FE.id='. $date_id;
    }
   
    elseif (!empty($date)){
        $where .= ' AND FE.fecha="'. $date .'"';
    }

    if (!empty($room_id)) {
        $where .= ' AND L.id='. $room_id;
    }

    if (!empty($user_id)) {
        $where .= ' AND SP.id='. $user_id;
    }

    $where .= ' GROUP BY EO.id_evento';

    return get_info($where, 'event', '', $order);
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

function schedule_has_events() {
    $events = count_records('evento');

    return $events > 0;
}

function level_admin($tadmin) {
    // 3 evaluador
    // 2 parcial
    // 1 root
    global $USER;
 
    if (!empty($USER->id_tadmin)) {
        return ($USER->id_tadmin <= $tadmin);
    } 

    return false;
}

function events_for($user_type, $user_id) {
    $result = 0;

    if ($user_type == 'speaker') {
        $query = 'SELECT E.id FROM evento E
                JOIN propuesta P ON P.id = E.id_propuesta
                JOIN ponente SP ON SP.id = P.id_ponente
                WHERE SP.id = '.$user_id;
        
        $result = count_records_sql($query);
    }
   
    elseif ($user_type == 'person') {
        
    }

    return ($result > 0);
}

?>
