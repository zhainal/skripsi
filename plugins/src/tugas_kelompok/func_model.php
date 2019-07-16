<?php

function tk_link_profil_siswa($siswa_id)
{
    if (is_admin()) {
        return site_url('siswa/detail/1/' . $siswa_id);
    } else {
        return site_url('siswa/detail/' . $siswa_id);
    }
}

function tk_nilai_anggota($act, $param1 = null, $param2 = null)
{
    $CI =& get_instance();
    $table = $CI->db->dbprefix . 'tk_nilai_anggota';

    switch ($act) {
        case 'delete':
            $CI->db->where('anggota_id', $param1);
            $CI->db->delete($table);
        break;

        case 'retrieve':
            foreach ($param1 as $key => $val) {
                $CI->db->where($key, $val);
            }
            $query = $CI->db->get($table);
            return $query->row_array();
        break;

        case 'insert':
            $CI->db->insert($table, $param1);
            return $CI->db->insert_id();
        break;

        case 'update':
            $CI->db->where('id', $param1);
            $CI->db->update($table, $param2);
            return true;
        break;
    }
}

function tk_penilaian_anggota($act, $param1 = null, $param2 = null)
{
    $CI =& get_instance();
    $table = $CI->db->dbprefix . 'tk_penilaian_anggota';

    switch ($act) {
        case 'delete':
            $CI->db->where('anggota_id', $param1);
            $CI->db->or_where('penilai_anggota_id', $param1);
            $CI->db->delete($table);
        break;

        case 'insert':
            $CI->db->insert($table, $param1);
            return $CI->db->insert_id();
        break;

        case 'retrieve':
            foreach ($param1 as $key => $val) {
                $CI->db->where($key, $val);
            }
            $query = $CI->db->get($table);
            return $query->row_array();
        break;

        case 'retrieve_all':
            $penilai_anggota_id = $param1;
            $CI->db->where('penilai_anggota_id', $penilai_anggota_id);
            $query = $CI->db->get($table);
            $results = $query->result_array();

            foreach ($results as $key => $val) {
                $val['anggota'] = tk_anggota('retrieve_by_id', $val['anggota_id']);
                $val['anggota']['siswa'] = $CI->siswa_model->retrieve($val['anggota']['siswa_id']);
                $val['anggota']['siswa']['link_profil'] = tk_link_profil_siswa($val['anggota']['siswa_id']);

                $results[$key] = $val;
            }

            return $results;
        break;

        case 'update':
            $CI->db->where('id', $param1);
            $CI->db->update($table, $param2);
            return true;
        break;
    }
}

function tk_kerjaan($act, $param1 = null, $param2 = null)
{
    $CI =& get_instance();
    $table = $CI->db->dbprefix . 'tk_kerjaan';
    $table_a = $CI->db->dbprefix . 'tk_anggota';

    switch ($act) {
        case 'delete':
            $CI->db->where('anggota_id', $param1);
            $CI->db->delete($table);
        break;

        case 'retrieve':
            $CI->db->where('anggota_id', $param1);
            $query = $CI->db->get($table);
            return $query->row_array();
        break;

        case 'insert':
            $CI->db->insert($table, $param1);
            return $CI->db->insert_id();
        break;

        case 'update':
            $CI->db->where('id', $param1);
            $CI->db->update($table, $param2);
            return true;
        break;

        case 'count_by_kelompok':
            $kelompok_id = $param1;
            $query = $CI->db->query("SELECT {$table}.* FROM {$table} WHERE {$table}.anggota_id IN (SELECT {$table_a}.id FROM {$table_a} WHERE {$table_a}.kelompok_id = '{$kelompok_id}') ORDER BY {$table}.tgl_input");
            return $query->num_rows();
        break;

        case 'retrieve_all_by_kelompok':
            $kelompok_id = $param1;
            $query = $CI->db->query("SELECT {$table}.* FROM {$table} WHERE {$table}.anggota_id IN (SELECT {$table_a}.id FROM {$table_a} WHERE {$table_a}.kelompok_id = '{$kelompok_id}') ORDER BY {$table}.tgl_input");
            $results = $query->result_array();

            foreach ($results as $key => $val) {
                $val['anggota'] = tk_anggota('retrieve_by_id', $val['anggota_id']);
                $val['anggota']['siswa'] = $CI->siswa_model->retrieve($val['anggota']['siswa_id']);
                $val['anggota']['siswa']['link_profil'] = tk_link_profil_siswa($val['anggota']['siswa_id']);

                $results[$key] = $val;
            }

            return $results;
        break;
    }
}

function tk_nilai_kelompok($act, $param1 = null, $param2 = null)
{
    $CI =& get_instance();
    $table = $CI->db->dbprefix . 'tk_nilai_kelompok';

    switch ($act) {
        case 'retrieve':
            $CI->db->where('kelompok_id', $param1);
            $query = $CI->db->get($table);
            return $query->row_array();
        break;

        case 'insert':
            $CI->db->insert($table, $param1);
            return $CI->db->insert_id();
        break;

        case 'update':
            $CI->db->where('id', $param1);
            $CI->db->update($table, $param2);
            return true;
        break;
    }
}

function tk_anggota($act, $param1 = null, $param2 = null, $param3 = null)
{
    $CI =& get_instance();
    $table = $CI->db->dbprefix . 'tk_anggota';
    $table_k = $CI->db->dbprefix . 'tk_kelompok';

    switch ($act) {
        case 'delete':
            $CI->db->where('id', $param1);
            $CI->db->delete($table);
        break;

        case 'insert':
            $CI->db->insert($table, $param1);
            return $CI->db->insert_id();
        break;

        case 'check_siswa':
            $tugas_id = $param1;
            $siswa_id = $param2;
            $query = $CI->db->query("SELECT COUNT(*) jml FROM {$table} WHERE {$table}.siswa_id = '{$siswa_id}' AND {$table}.kelompok_id IN (SELECT {$table_k}.id FROM {$table_k} WHERE {$table_k}.tugas_id = '{$tugas_id}')");
            $result = $query->row_array();
            return isset($result['jml']) ? $result['jml'] : 0;
        break;

        case 'check_siswa_edit':
            $tugas_id = $param1;
            $siswa_id = $param2;
            $kelompok_id = $param3;
            $query = $CI->db->query("SELECT COUNT(*) jml FROM {$table} WHERE {$table}.siswa_id = '{$siswa_id}' AND {$table}.kelompok_id IN (SELECT {$table_k}.id FROM {$table_k} WHERE {$table_k}.tugas_id = '{$tugas_id}' AND {$table_k}.id != '{$kelompok_id}')");
            $result = $query->row_array();
            return isset($result['jml']) ? $result['jml'] : 0;
        break;

        case 'retrieve':
            $kelompok_id = $param1;
            $siswa_id = $param2;
            $CI->db->where('kelompok_id', $kelompok_id);
            $CI->db->where('siswa_id', $siswa_id);
            $query = $CI->db->get($table);
            return $query->row_array();
        break;

        case 'retrieve_by_id':
            $anggota_id = $param1;
            $CI->db->where('id', $anggota_id);
            $query = $CI->db->get($table);
            return $query->row_array();
        break;

        case 'retrieve_all':
            $CI->db->where('kelompok_id', $param1);
            $results = $CI->db->get($table);
            $results = $results->result_array();

            foreach ($results as $key => $val) {
                $val['siswa'] = $CI->siswa_model->retrieve($val['siswa_id']);
                $val['siswa']['link_profil'] = tk_link_profil_siswa($val['siswa_id']);
                $results[$key] = $val;
            }

            return $results;
        break;
    }
}

function tk_kelompok($act, $param1 = null, $param2 = null)
{
    $CI =& get_instance();
    $table = $CI->db->dbprefix . 'tk_kelompok';
    $table_a = $CI->db->dbprefix . 'tk_anggota';

    switch ($act) {
        case 'insert':
            $CI->db->insert($table, $param1);
            return $CI->db->insert_id();
        break;

        case 'retrieve':
            $CI->db->where('id', $param1);
            $query = $CI->db->get($table);
            return $query->row_array();
        break;

        case 'retrieve_on_siswa':
            $tugas_id = $param1;
            $siswa_id = $param2;
            $query = $CI->db->query("SELECT {$table}.* FROM {$table} WHERE {$table}.tugas_id = '{$tugas_id}' AND {$table}.id IN (SELECT {$table_a}.kelompok_id FROM {$table_a} WHERE {$table_a}.siswa_id = '{$siswa_id}')");
            return $query->row_array();
        break;

        case 'retrieve_all':
            $CI->db->order_by('nama');
            $CI->db->where('tugas_id', $param1);
            $results = $CI->db->get($table);
            $results = $results->result_array();

            foreach ($results as $key => $val) {
                $val['anggota'] = tk_anggota('retrieve_all', $val['id']);
                $val['jml_kerjaan'] = tk_kerjaan('count_by_kelompok', $val['id']);
                $results[$key]  = $val;
            }

            return $results;
        break;

        case 'update':
            $CI->db->where('id', $param1);
            $CI->db->update($table, $param2);
            return true;
        break;
    }
}

function tk_tugas($act, $param1 = null, $param2 = null)
{
    $CI =& get_instance();
    $table = $CI->db->dbprefix . 'tk_tugas';

    switch ($act) {
        case 'retrieve':
            $CI->db->where('id', $param1);
            $result = $CI->db->get($table, 1);
            return $result->row_array();
        break;

        case 'insert':
            $CI->db->insert($table, $param1);
            return $CI->db->insert_id();
        break;

        case 'update':
            $CI->db->where('id', $param1);
            $CI->db->update($table, $param2);
            return true;
        break;

        case 'retrieve_all':
            $no_of_records = isset($param1['no_of_records']) ? $param1['no_of_records'] : 20;
            $page_no       = isset($param1['page_no']) ? $param1['page_no'] : 1;

            $where = array();

            $like = 0;
            if (!empty($param1['judul'])) {
                $where['judul'] = array($param1['judul'], 'like');
                $like = 1;
            }

            if (!empty($param1['mapel_id'])) {
                $where['mapel_id'] = array($param1['mapel_id'], 'where_in');
            }

            if (!empty($param1['kelas_id'])) {
                $where['kelas_id'] = array($param1['kelas_id'], 'where_in');
            }

            if (!empty($param1['status'])) {
                $where['status'] = array($param1['status'], 'where_in');
            }

            if (!empty($param1['pengajar_id'])) {
                $where['pengajar_id'] = array($param1['pengajar_id'], 'where_in');
            }

            $orderby = array('id' => 'DESC');
            $data = $CI->pager->set($table, $no_of_records, $page_no, $where, $orderby);
            foreach ($data['results'] as $key => $val) {
                $data['results'][$key] = tk_tugas('format', $val);
            }

            return $data;
        break;

        case 'get_status':
            $label_status = "";
            if ($param1 == 1) {
                $label_status = "draft";
            } elseif ($param1 == 2) {
                $label_status = "terbit";
            } elseif ($param1 == 3) {
                $label_status = "tutup";
            }

            return $label_status;
        break;

        case 'format':
            # cari mapel
            if (!empty($GLOBALS['tk_tugas_format_mapel'][$param1['mapel_id']])) {
                $param1['mapel'] = $GLOBALS['tk_tugas_format_mapel'][$param1['mapel_id']];
            } else {
                $param1['mapel'] = $CI->mapel_model->retrieve($param1['mapel_id']);
                $GLOBALS['tk_tugas_format_mapel'][$param1['mapel_id']] = $param1['mapel'];
            }

            # cari kelas
            if (!empty($GLOBALS['tk_tugas_format_kelas'][$param1['kelas_id']])) {
                $param1['kelas'] = $GLOBALS['tk_tugas_format_kelas'][$param1['kelas_id']];
            } else {
                $param1['kelas'] = $CI->kelas_model->retrieve($param1['kelas_id'], true);
                $GLOBALS['tk_tugas_format_kelas'][$param1['kelas_id']] = $param1['kelas'];
            }

            # status
            $param1['label_status'] = tk_tugas('get_status', $param1['status']);

            # pengajar
            if (!empty($GLOBALS['tk_tugas_format_pengajar'][$param1['pengajar_id']])) {
                $param1['pengajar'] = $GLOBALS['tk_tugas_format_pengajar'][$param1['pengajar_id']];
            } else {
                $pengajar = $CI->pengajar_model->retrieve($param1['pengajar_id']);
                $param1['pengajar'] = $pengajar;
                if (is_admin()) {
                    $param1['pengajar']['link_profil'] = site_url('pengajar/detail/'.$pengajar['status_id'].'/'.$pengajar['id']);
                } else {
                    $param1['pengajar']['link_profil'] = site_url('pengajar/detail/'.$pengajar['id']);
                }

                $GLOBALS['tk_tugas_format_pengajar'][$param1['pengajar_id']] = $param1['pengajar'];
            }

            return $param1;
        break;
    }
}


function tk_table_create()
{
    $CI =& get_instance();
    $prefix = $CI->db->dbprefix;

    $field_id = "sukses-create-table-tugas-kelompok";
    $check    = retrieve_field($field_id);
    if (!empty($check['value'])) {
        return true;
    }

    if ($CI->db->table_exists('tk_tugas') == false) {
        $CI->db->query("CREATE TABLE IF NOT EXISTS `{$prefix}tk_tugas` (
          `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `judul` varchar(255) NOT NULL,
          `mapel_id` int NOT NULL,
          `kelas_id` int NOT NULL,
          `status` tinyint(1) NOT NULL COMMENT '1=draft, 2=terbit, 3=tutup',
          `pengajar_id` int NOT NULL
        ) ENGINE='MyISAM';");
    }

    if ($CI->db->table_exists('tk_kelompok') == false) {
        $CI->db->query("CREATE TABLE IF NOT EXISTS `{$prefix}tk_kelompok` (
          `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `tugas_id` int NOT NULL,
          `nama` varchar(255) NOT NULL,
          `intruksi` text NOT NULL
        ) ENGINE='MyISAM';");
    }

    if ($CI->db->table_exists('tk_anggota') == false) {
        $CI->db->query("CREATE TABLE IF NOT EXISTS `{$prefix}tk_anggota` (
          `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `kelompok_id` int NOT NULL,
          `siswa_id` int NOT NULL
        ) ENGINE='MyISAM';");
    }

    if ($CI->db->table_exists('tk_kerjaan') == false) {
        $CI->db->query("CREATE TABLE IF NOT EXISTS `{$prefix}tk_kerjaan` (
          `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `anggota_id` int NOT NULL,
          `konten` text NOT NULL,
          `tgl_input` datetime NOT NULL
        ) ENGINE='MyISAM';");
    }

    if ($CI->db->table_exists('tk_penilaian_anggota') == false) {
        $CI->db->query("CREATE TABLE IF NOT EXISTS `{$prefix}tk_penilaian_anggota` (
          `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `kelompok_id` int NOT NULL,
          `penilai_anggota_id` int NOT NULL,
          `anggota_id` int NOT NULL,
          `nilai` varchar(3) NOT NULL,
          `alasan` text NOT NULL
        ) ENGINE='MyISAM';");
    }

    if ($CI->db->table_exists('tk_nilai_kelompok') == false) {
        $CI->db->query("CREATE TABLE IF NOT EXISTS `{$prefix}tk_nilai_kelompok` (
          `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `kelompok_id` int NOT NULL,
          `nilai` float NOT NULL,
          `catatan` text NOT NULL
        ) ENGINE='MyISAM';");
    }

    if ($CI->db->table_exists('tk_nilai_anggota') == false) {
        $CI->db->query("CREATE TABLE IF NOT EXISTS `{$prefix}tk_nilai_anggota` (
          `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `kelompok_id` int NOT NULL,
          `anggota_id` int NOT NULL,
          `nilai` float NOT NULL,
          `catatan` text NOT NULL
        ) ENGINE='MyISAM';");
    }

    create_field($field_id, "sukses create table tugas kelompok", 1);

    return true;
}
