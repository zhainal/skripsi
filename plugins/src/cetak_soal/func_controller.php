<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function construct()
{
    # cek session
    must_login();
}

function cetak($act = 'print', $tugas_id)
{
    $CI =& get_instance();

    # harus admin atau pengajar
    if (!is_admin() AND !is_pengajar()) {
        $CI->session->set_flashdata('tugas', get_alert('warning', 'Akses Ditolak.'));
        redirect('tugas');
    }

    $tugas_id = (int)$tugas_id;
    $tugas = $CI->tugas_model->retrieve($tugas_id);
    if (empty($tugas) OR $tugas['type_id'] == 1) {
        redirect('tugas/index');
    }

    # jika sebagai pengajar, cek kepemilikan
    if (is_pengajar() AND $tugas['pengajar_id'] != get_sess_data('user', 'id')) {
        redirect('tugas');
    }

    $retrieve_all = $CI->tugas_model->retrieve_all_pertanyaan(
        "all",
        1,
        $tugas['id'],
        'DESC'
    );

    # jika pilihan ganda
    if ($tugas['type_id'] == 3) {
        foreach ($retrieve_all as &$val) {
            $val['pilihan'] = $CI->tugas_model->retrieve_all_pilihan($val['id']);
        }
    }

    $data['tugas']      = plugin_helper('cetak_soal', 'formatDataTugas', array($tugas));
    $data['pertanyaan'] = $retrieve_all;

    switch ($act) {
        case 'word':
            header("Content-type: application/vnd.ms-word");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Disposition: attachment;Filename=soal-" . url_title($data['tugas']['judul'], '-', true)  . ".doc");
            $CI->twig->display('cetak-soal-tugas.html', $data);
        break;

        default:
        case 'print':
            $CI->twig->display('cetak-soal-tugas.html', $data);
        break;
    }
}
