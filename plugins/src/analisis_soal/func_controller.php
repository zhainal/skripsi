<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function construct()
{
    # cek session
    must_login();
}

function index($tugas_id = "")
{
    $CI =& get_instance();

    $tugas_id = (int)$tugas_id;
    $tugas    = $CI->tugas_model->retrieve($tugas_id);
    if (empty($tugas)) {
        redirect('tugas');
    }

    # jika pengajar atau admin
    if (is_pengajar() OR is_admin()) {
        # ini harus ganda
        if ($tugas['type_id'] != 3) {
            redirect('tugas');
        }

        # ambil semua soal pada tugas ini
        $retrieve_all_pertanyaan = $CI->tugas_model->retrieve_all_pertanyaan('all', 1, $tugas['id']);
        $l = 1;

        $results      = array();
        $list_kunci   = array();
        $list_jawaban = array();
        foreach ($retrieve_all_pertanyaan as $p) {
            $pilihan      = $CI->tugas_model->retrieve_all_pilihan($p['id']);
            $p['pilihan'] = $pilihan;
            $results[$l]  = $p;

            $list_kunci[$p['id']]            = get_kunci_pilihan($pilihan);
            $list_jawaban[$p['id']]['benar'] = 0;
            $list_jawaban[$p['id']]['dari']  = 0;
            $l++;
        }

        # ambil history
        $retrieve_all_history = $CI->tugas_model->retrieve_all_history($tugas['id']);
        foreach ($retrieve_all_history as $history) {
            $history_value = json_decode($history['value'], 1);
            if (isset($history_value['jawaban'])) {
                foreach ($history_value['jawaban'] as $pertanyaan_id => $pilihan_id) {
                    if (isset($list_kunci[$pertanyaan_id])) {
                        if ($list_kunci[$pertanyaan_id] == $pilihan_id) {
                            $list_jawaban[$pertanyaan_id]['benar'] = $list_jawaban[$pertanyaan_id]['benar'] + 1;
                        }
                        $list_jawaban[$pertanyaan_id]['dari']  = $list_jawaban[$pertanyaan_id]['dari'] + 1;
                    }
                }
            }
        }

        # menghitung indeks kesukaran
        foreach ($list_jawaban as $pertanyaan_id => &$value) {
            if ($value['dari'] > 0) {
                $indeks = ($value['benar'] / $value['dari']);
                $indeks = round($indeks, 2);

                /**
                 * <= 0.3 = sulit
                 * >0.3 <=0.7 = sedang
                 * >0.7 = mudah
                 */
                if ($indeks <= 0.3) {
                    $value['kategori'] = 'sulit';
                } elseif ($indeks > 0.3 AND $indeks <= 0.7) {
                    $value['kategori'] = 'sedang';
                } elseif ($indeks > 0.7 ) {
                    $value['kategori'] = 'mudah';
                }
            } else {
                $value['kategori'] = 'belum dijawab';
            }
        }

        $data['analisis']   = $list_jawaban;
        $data['tugas']      = plugin_helper('analisis_soal', 'formatDataTugas', array($tugas));
        $data['pertanyaan'] = $results;

        # panggil datatables dan combobox
        $data['comp_js'] = load_comp_js(array(
            base_url('assets/comp/datatables/jquery.dataTables.js'),
            base_url('assets/comp/datatables/datatable-bootstrap2.js'),
        ));

        $data['comp_css'] = load_comp_css(array(
            base_url('assets/comp/datatables/datatable-bootstrap2.css')
        ));

        if (!empty($_GET['mode']) AND $_GET['mode'] == 'print') {
            $CI->twig->display('analisis-soal-print.html', $data);
        } else {
            $CI->twig->display('analisis-soal.html', $data);
        }

    } else {
        redirect('tugas');
    }
}
