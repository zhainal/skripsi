<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function formatDataTugas($val)
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
    $tugas_kelas = $CI->tugas_model->retrieve_all_kelas($val['id']);
    foreach ($tugas_kelas as $mk) {
        $kelas = $CI->kelas_model->retrieve($mk['kelas_id']);
        $val['tugas_kelas'][] = $kelas;
    }

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