<?php

function ct_retrieve_all_pertanyaan(
    $no_of_records = 10,
    $page_no       = 1,
    $tugas_id      = null,
    $sort          = 'ASC',
    $pertanyaan_id = array()
) {
    $CI =& get_instance();

    if ($no_of_records == 'all') {
        if (!is_null($tugas_id)) {
            $CI->db->where('tugas_id', $tugas_id);
        }
        $CI->db->where('aktif', 1);
        $CI->db->order_by('urutan', $sort);
        $result = $CI->db->get('tugas_pertanyaan');
        $data   = $result->result_array();

    } else {
        $no_of_records = (int)$no_of_records;
        $page_no       = (int)$page_no;

        $where = array();
        if (!is_null($tugas_id)) {
            $tugas_id = (int)$tugas_id;
            $where['tugas_id'] = array($tugas_id, 'where');
        }

        # tampilkan hanya yang aktif saja
        $where['aktif'] = array(1, 'where');

        $orderby = array('urutan' => $sort);

        if (!empty($pertanyaan_id)) {
            $where['id'] = array($pertanyaan_id, 'where_in');
            $orderby     = array("FIELD (id, " . implode(',', $pertanyaan_id) . ")" => 'protect_identifiers_false');
        }

        $data = $CI->pager->set('tugas_pertanyaan', $no_of_records, $page_no, $where, $orderby);
    }

    return $data;
}