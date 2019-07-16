<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Configurasi plugin bunk soal
 * @return array
 */
function bs_config($act = "get", $new_value = array())
{
    $default_config = array(
        'tampil_dipengajar' => 2, // 1=semua tampil, 2=hanya yg dibuat
        'tampil_diadmin' => 1, // 1=semua tampil, 2=hanya yg dibuat
    );

    $id = "pengaturan-bank-soal";
    $label = "Pengaturan plugin bank_soal";
    $config = retrieve_field($id);
    if (empty($config['value'])) {
        create_field($id, $label, json_encode($default_config));
        $config = retrieve_field($id);
    }

    switch ($act) {
        case 'get':
            return json_decode($config['value'], 1);
            break;

        case 'update':
            update_field($id, $label, json_encode($new_value));
            return true;
            break;
    }
}

/**
 * Method untuk mendapatkan satu record bank soal
 *
 * @param  integer|array $id
 * @return array
 */
function bs_retrieve($id)
{
    $CI =& get_instance();

    if (is_array($id)) {
        $CI->db->where_in('id', $id);
        $results = $CI->db->get('bank_soal');
        return $results->result_array();
    } else {
        $CI->db->where('id', $id);
        $result = $CI->db->get('bank_soal');
        return $result->row_array();
    }
}

/**
 * Method untuk mengambil semua record bank soal
 * @return array
 */
function bs_retrieve_all(
    $no_of_records = 20,
    $page_no       = 1,
    $pengajar_id   = array(),
    $mapel_id      = 0,
    $keyword       = null
){
    $CI =& get_instance();

    $where = array();
    $orderby['id'] = 'DESC';

    if (!empty($pengajar_id)) {
        $where['pengajar_id'] = array($pengajar_id, 'where_in');
    }

    if (!empty($mapel_id)) {
        $where['mapel_id'] = array($mapel_id, 'where');
    }

    if (!empty($keyword) AND empty($pengajar_id)) {
        $keyword = (string)trim($keyword);
        $where["(id LIKE '%" . $CI->db->escape_like_str($keyword) . "%' OR pertanyaan LIKE '%" . $CI->db->escape_like_str($keyword) . "%' OR pilihan_a LIKE '%" . $CI->db->escape_like_str($keyword) . "%'  OR pilihan_b LIKE '%" . $CI->db->escape_like_str($keyword) . "%'  OR pilihan_c LIKE '%" . $CI->db->escape_like_str($keyword) . "%'  OR pilihan_d LIKE '%" . $CI->db->escape_like_str($keyword) . "%' OR pilihan_e LIKE '%" . $CI->db->escape_like_str($keyword) . "%')"] = array(null, 'where');
    }

    $data = $CI->pager->set('bank_soal', $no_of_records, $page_no, $where, $orderby);

    return $data;
}

/**
 * Method untuk menghapus record bank_soal
 *
 * @param  integer $id
 * @return boolean
 */
function bs_delete($id)
{
    $CI =& get_instance();

    $CI->db->where('id', $id);
    $CI->db->delete('bank_soal');
    return true;
}

/**
 * Method untuk update bank soal
 *
 * @param  integer $id
 * @param  integer $pengajar_id
 * @param  integer $mapel_id
 * @param  string  $pertanyaan
 * @param  string  $pilihan_a
 * @param  string  $pilihan_b
 * @param  string  $pilihan_c
 * @param  string  $pilihan_d
 * @param  string  $pilihan_e
 * @param  string  $kunci
 * @return boolean
 */
function bs_update(
    $id,
    $pengajar_id,
    $mapel_id,
    $pertanyaan,
    $pilihan_a = null,
    $pilihan_b = null,
    $pilihan_c = null,
    $pilihan_d = null,
    $pilihan_e = null,
    $kunci     = null
) {
    $CI =& get_instance();

    $CI->db->where('id', $id);
    $CI->db->update('bank_soal', array(
        'pengajar_id' => $pengajar_id,
        'mapel_id'    => $mapel_id,
        'pertanyaan'  => $pertanyaan,
        'pilihan_a'   => $pilihan_a,
        'pilihan_b'   => $pilihan_b,
        'pilihan_c'   => $pilihan_c,
        'pilihan_d'   => $pilihan_d,
        'pilihan_e'   => $pilihan_e,
        'kunci'       => $kunci
    ));
    return true;
}

/**
 * Method untuk create bank soal
 *
 * @param  integer $pengajar_id
 * @param  integer $mapel_id
 * @param  string  $pertanyaan
 * @param  string  $pilihan_a
 * @param  string  $pilihan_b
 * @param  string  $pilihan_c
 * @param  string  $pilihan_d
 * @param  string  $pilihan_e
 * @param  string  $kunci
 * @return integer last insert id
 */
function bs_create(
    $pengajar_id,
    $mapel_id,
    $pertanyaan,
    $pilihan_a = null,
    $pilihan_b = null,
    $pilihan_c = null,
    $pilihan_d = null,
    $pilihan_e = null,
    $kunci     = null
) {
    $CI =& get_instance();

    $CI->db->insert('bank_soal', array(
        'pengajar_id' => $pengajar_id,
        'mapel_id'    => $mapel_id,
        'pertanyaan'  => $pertanyaan,
        'pilihan_a'   => $pilihan_a,
        'pilihan_b'   => $pilihan_b,
        'pilihan_c'   => $pilihan_c,
        'pilihan_d'   => $pilihan_d,
        'pilihan_e'   => $pilihan_e,
        'kunci'       => $kunci
    ));
    return $CI->db->insert_id();
}

/**
 * Method untuk create table
 * @return boolean
 */
function bs_create_table()
{
    $CI =& get_instance();

    # cek sudah ada belum
    if (!$CI->db->table_exists('bank_soal')) {
        $prefix = $CI->db->dbprefix;

        $CI->db->query("CREATE TABLE IF NOT EXISTS `{$prefix}bank_soal` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `pengajar_id` int(11) NOT NULL,
          `mapel_id` int(11) DEFAULT NULL,
          `pertanyaan` text NOT NULL,
          `pilihan_a` text,
          `pilihan_b` text,
          `pilihan_c` text,
          `pilihan_d` text,
          `pilihan_e` text,
          `kunci` char(1) DEFAULT NULL COMMENT 'a,b,c,d,e',
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    }

    return true;
}
