<?php

function kd_tugas_retrieve_all()
{
    $CI =& get_instance();

    if (is_pengajar()) {
        $CI->db->select('kd_tugas.*');
        $CI->db->join('tugas', 'kd_tugas.tugas_id = tugas.id', 'inner');
        $CI->db->where('tugas.pengajar_id', get_sess_data('user', 'id'));
    }

    $CI->db->order_by('kd_tugas.id', 'desc');
    $results = $CI->db->get('kd_tugas');
    $results = $results->result_array();

    foreach ($results as $key => $row) {
        $format_data = kd_tugas_format_item($row);
        $results[$key] = array_merge($row, $format_data);
    }

    return $results;
}

function kd_tugas_format_item($row)
{
    $CI =& get_instance();
    $tugas = $CI->tugas_model->retrieve($row['tugas_id']);
    $return['tugas'] = kd_tugas_format($tugas);

    # ambil kd_tugas_kd
    $arr_kd_mapel_nama = array();
    $arr_kd_mapel_id = array();
    $kd_tugas_kd = kd_tugas_kd_retrieve_all($row['id']);
    foreach ($kd_tugas_kd as $key2 => $val2) {
        $decode = json_decode($val2['no_soal'], 1);
        $val2['no_soal_arr'] = $decode;

        # ambil kd_mapel
        $kd_mapel = kd_mapel_retrieve($val2['kd_mapel_id']);
        $val2['kd_mapel'] = $kd_mapel;
        $kd_tugas_kd[$key2] = $val2;

        $arr_kd_mapel_nama[] = $kd_mapel['nama'];
        $arr_kd_mapel_id[] = $kd_mapel['id'];
    }

    $return['kd_tugas_kd'] = $kd_tugas_kd;
    $return['kd_mapel_nama'] = implode(", ", $arr_kd_mapel_nama);
    $return['kd_mapel_id'] = implode(", ", $arr_kd_mapel_id);

    return $return;
}

function kd_tugas_ganda_retrieve_all()
{
    $CI =& get_instance();

    $CI->db->where('type_id', 3);
    if (is_pengajar()) {
        $CI->db->where('pengajar_id', get_sess_data('user', 'id'));
    }

    $CI->db->order_by('id', 'desc');
    $results = $CI->db->get('tugas');
    $results = $results->result_array();

    return $results;
}

function kd_mapel_retrieve_all($mapel_id, $only_active = true)
{
    $CI =& get_instance();

    if ($only_active) {
        $CI->db->where('aktif', 1);
    }

    $CI->db->where('mapel_id', $mapel_id);
    $CI->db->order_by('id', 'desc');
    $results = $CI->db->get('kd_mapel');
    $results = $results->result_array();

    return $results;
}

function kd_mapel_retrieve($id)
{
    $CI =& get_instance();
    $CI->db->where('id', $id);
    $result = $CI->db->get('kd_mapel');
    return $result->row_array();
}

function kd_mapel_add($mapel_id, $nama_kd)
{
    $CI =& get_instance();
    $CI->db->insert('kd_mapel', array(
        'mapel_id' => $mapel_id,
        'nama'     => $nama_kd,
        'aktif'    => 1
    ));

    return $CI->db->insert_id();
}

function kd_mapel_edit($id, $nama_kd)
{
    $CI =& get_instance();
    $CI->db->where('id', $id);
    $CI->db->update('kd_mapel', array(
        'nama' => $nama_kd
    ));

    return true;
}

function kd_mapel_delete($id)
{
    $CI =& get_instance();
    $CI->db->where('id', $id);
    $CI->db->update('kd_mapel', array(
        'aktif' => 0
    ));

    return true;
}

function kd_tugas_create($tugas_id, $nilai_lulus)
{
    $CI =& get_instance();
    $CI->db->insert('kd_tugas', array(
        'tugas_id' => $tugas_id,
        'nilai_lulus' => $nilai_lulus
    ));

    return $CI->db->insert_id();
}

function kd_tugas_update($id, $nilai_lulus)
{
    $CI =& get_instance();
    $CI->db->where('id', $id);
    $CI->db->update('kd_tugas', array('nilai_lulus' => $nilai_lulus));
    return true;
}

function kd_tugas_retrieve($id)
{
    $CI =& get_instance();
    $CI->db->where('id', $id);
    $result = $CI->db->get('kd_tugas');
    return $result->row_array();
}

function kd_tugas_delete($id)
{
    $CI =& get_instance();
    $CI->db->where('id', $id);
    $CI->db->delete('kd_tugas');
    return true;
}

function kd_tugas_kd_create($kd_tugas_id, $kd_mapel_id)
{
    $CI =& get_instance();
    $CI->db->insert('kd_tugas_kd', array(
        'kd_tugas_id' => $kd_tugas_id,
        'kd_mapel_id' => $kd_mapel_id,
        'no_soal' => ""
    ));

    return $CI->db->insert_id();
}

function kd_tugas_kd_delete_by_tugas($kd_tugas_id)
{
    $CI =& get_instance();
    $CI->db->where('kd_tugas_id', $kd_tugas_id);
    $CI->db->delete('kd_tugas_kd');
    return true;
}

function kd_tugas_kd_delete($id)
{
    $CI =& get_instance();
    $CI->db->where('id', $id);
    $CI->db->delete('kd_tugas_kd');
    return true;
}

function kd_tugas_kd_update($id, $no_soal)
{
    $CI =& get_instance();
    $CI->db->where('id', $id);
    $CI->db->update('kd_tugas_kd', array('no_soal' => $no_soal));
}

function kd_tugas_kd_retrieve_all($kd_tugas_id)
{
    $CI =& get_instance();
    $CI->db->where('kd_tugas_id', $kd_tugas_id);
    $CI->db->order_by('id', 'desc');
    $results = $CI->db->get('kd_tugas_kd');
    return $results->result_array();
}

function kd_tugas_kd_retrieve_by_id($id)
{
    $CI =& get_instance();
    $CI->db->where('id', $id);
    $result = $CI->db->get('kd_tugas_kd');
    return $result->row_array();
}

function kd_tugas_kd_retrieve($kd_tugas_id, $kd_mapel_id)
{
    $CI =& get_instance();
    $CI->db->where('kd_tugas_id', $kd_tugas_id);
    $CI->db->where('kd_mapel_id', $kd_mapel_id);
    $result = $CI->db->get('kd_tugas_kd');
    return $result->row_array();
}

function kd_tugas_format($val)
{
    $CI =& get_instance();

    # cari pembuatnya
    if (!empty($val['pengajar_id'])) {
        $pengajar = $CI->pengajar_model->retrieve($val['pengajar_id']);
        $val['pembuat'] = $pengajar;
        if (is_admin()) {
            $val['pembuat']['link_profil'] = site_url('pengajar/detail/'.$pengajar['status_id'].'/'.$pengajar['id']);
        } else {
            $val['pembuat']['link_profil'] = site_url('pengajar/detail/'.$pengajar['id']);
        }
    }

    # cari tugas kelas
    $arr_kelas_nama = array();
    $tugas_kelas = $CI->tugas_model->retrieve_all_kelas($val['id']);
    foreach ($tugas_kelas as $mk) {
        $kelas = $CI->kelas_model->retrieve($mk['kelas_id']);
        $val['tugas_kelas'][] = $kelas;

        $arr_kelas_nama[] = $kelas['nama'];
    }
    $val['nama_kelas'] = implode(", ", $arr_kelas_nama);

    # cari matapelajarannya
    $val['mapel'] = $CI->mapel_model->retrieve($val['mapel_id']);

    # type label
    if ($val['type_id'] == 1) {
        $val['type_label'] = 'Upload';
    }
    if ($val['type_id'] == 2) {
        $val['type_label'] = 'Essay';
    }
    if ($val['type_id'] == 3) {
        $val['type_label'] = 'Ganda';
    }

    return $val;
}

function kd_table_create()
{
    $CI =& get_instance();
    $prefix = $CI->db->dbprefix;

    if ($CI->db->table_exists('kd_mapel') == false) {
        $CI->db->query("CREATE TABLE IF NOT EXISTS `{$prefix}kd_mapel` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `mapel_id` int(11) NOT NULL,
          `nama` varchar(255) NOT NULL,
          `aktif` tinyint(1) NOT NULL DEFAULT '1',
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
    }

    if ($CI->db->table_exists('kd_tugas') == false) {
        $CI->db->query("CREATE TABLE IF NOT EXISTS `{$prefix}kd_tugas` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `tugas_id` int(11) NOT NULL,
          `nilai_lulus` decimal(5,2) NOT NULL DEFAULT '0.00',
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
    }

    if ($CI->db->table_exists('kd_tugas_kd') == false) {
        $CI->db->query("CREATE TABLE IF NOT EXISTS `{$prefix}kd_tugas_kd` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `kd_tugas_id` int(11) NOT NULL,
          `kd_mapel_id` int(11) NOT NULL,
          `no_soal` text NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
    }

    return true;
}
