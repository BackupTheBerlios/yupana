<?php

function get_info($where, $type='person', $limit='', $order='P.id, P.reg_time') {
    $record = new StdClass;

    if (!empty($limit) && $limit != 1) {
        $where .= ' LIMIT ' . $limit;
    }

    if (!empty($order)) {
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
    elseif ($type == 'proposal' && $limit != 1) {
        $query = '
            SELECT P.*, L.descr AS nivel, SP.login, SP.nombrep,
            SP.apellidos, T.descr AS tipo, ADM.login AS administrador,
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

    // one proposal, get event details
    elseif ($type == 'proposal' && $limit == 1) {
        $query = '
            SELECT P.*, L.descr AS nivel, SP.login, SP.nombrep,
            SP.apellidos, T.descr AS tipo, ADM.login AS administrador,
            O.descr AS orientacion, S.descr AS status,
            FE.fecha, R.nombre_lug AS lugar, EO.hora
            FROM propuesta P
            JOIN prop_nivel L ON L.id = P.id_nivel
            JOIN ponente SP ON SP.id = P.id_ponente
            JOIN prop_tipo T ON T.id = P.id_prop_tipo
            LEFT JOIN administrador ADM ON ADM.id = P.id_administrador
            JOIN orientacion O ON O.id = P.id_orientacion
            JOIN prop_status S ON S.id = P.id_status
            LEFT JOIN evento E ON E.id_propuesta = P.id
            LEFT JOIN evento_ocupa EO ON EO.id_evento = E.id
            LEFT JOIN fecha_evento FE ON FE.id = E.id_fecha
            LEFT JOINT lugar R ON R.id = E.id_lugar
            WHERE ' . $where;
    }

    // get only one record?
    if ($limit == 1) {
        $records = get_record_sql($query);
    } else {
        $records = get_records_sql($query);
    }

    return $records;
}

function get_admins($limit='') {
    return get_info('1=1', 'admin', $limit);
}

function get_speakers($limit='') {
    return get_info('1=1', 'speakers', $limit);
}

function get_persons($limit='') {
    return get_info('1=1', 'person', $limit);
}

function get_proposals($limit='') {
    return get_info('1=1', 'proposal', $limit);
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

function get_proposal($id) {
    return get_info('P.id='.$id, 'proposal', 1);
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

?>
