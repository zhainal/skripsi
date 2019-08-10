<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function construct()
{
    # cek session
    must_login();

    if (is_siswa()) {
        show_error("Akses ditolak!");
    }

    # include model
    include 'func_model.php';

    kd_table_create();
}

function index()
{
    $CI =& get_instance();

    $data['tugas'] = kd_tugas_retrieve_all();

    # panggil dataTables
    $data['comp_js'] = load_comp_js(array(
        base_url('assets/comp/datatables/jquery.dataTables.js'),
        base_url('assets/comp/datatables/datatable-bootstrap2.js'),
    ));

    $data['comp_css'] = load_comp_css(array(
        base_url('assets/comp/datatables/datatable-bootstrap2.css'),
    ));

    // pr($data);

    $CI->twig->display('kd-list-tugas.html', $data);
}

function add()
{
    $CI =& get_instance();

    $CI->form_validation->set_rules('tugas_id', 'Tugas', 'required');
    $CI->form_validation->set_rules('nilai_lulus', 'Nilai Minimal Lulus', 'required|decimal');
    $CI->form_validation->set_rules('kd_mapel_id[]', 'KD Mapel', 'required');
    if ($CI->form_validation->run() == true) {
        $tugas_id    = $CI->input->post('tugas_id', true);
        $nilai_lulus = $CI->input->post('nilai_lulus', true);
        $kd_mapel_id = $CI->input->post('kd_mapel_id', true);

        $kd_tugas_id = kd_tugas_create($tugas_id, $nilai_lulus);
        foreach ($kd_mapel_id as $val) {
            kd_tugas_kd_create($kd_tugas_id, $val);
        }

        $CI->session->set_flashdata('kd', get_alert('success', 'Analisis berhasil ditambahkan, silakan atur <b>KD No.Soal</b>.'));
        redirect('plugins/pencapaian_kd/kd_no_soal/' . $kd_tugas_id);
    }

    $data['tugas_ganda'] = kd_tugas_ganda_retrieve_all();
    $CI->twig->display('kd-tambah-tugas.html', $data);
}

function edit($id, $uri_back = "")
{
    $CI =& get_instance();

    $kd_tugas = kd_tugas_retrieve($id);
    if (empty($kd_tugas)) { die; }

    if (!empty($uri_back)) {
        $uri_back = deurl_redirect($uri_back);
    } else {
        $uri_back = site_url('plugins/pencapaian_kd');
    }
    $data['uri_back'] = $uri_back;

    $CI->form_validation->set_rules('tugas_id', 'Tugas', 'required');
    $CI->form_validation->set_rules('nilai_lulus', 'Nilai Minimal Lulus', 'required|decimal');
    $CI->form_validation->set_rules('kd_mapel_id[]', 'KD Mapel', 'required');
    if ($CI->form_validation->run() == true) {
        $kd_mapel_id = $CI->input->post('kd_mapel_id', true);
        $nilai_lulus = $CI->input->post('nilai_lulus', true);

        kd_tugas_update($kd_tugas['id'], $nilai_lulus);

        # ambil semua kd_tugas_kd
        $kd_tugas_mapel_id = array();
        $kd_tugas_kd = kd_tugas_kd_retrieve_all($id);
        foreach ($kd_tugas_kd as $key => $val) {
            $kd_tugas_mapel_id[] = $val['kd_mapel_id'];
        }

        if (count($kd_mapel_id) > count($kd_tugas_mapel_id)) {
            $beda = array_diff($kd_mapel_id, $kd_tugas_mapel_id);
        } else {
            $beda = array_diff($kd_tugas_mapel_id, $kd_mapel_id);
        }

        foreach ($beda as $val) {
            # kalo ada dihapus
            # kalo tidak ada di insert
            $check = kd_tugas_kd_retrieve($id, $val);
            if (empty($check)) {
                kd_tugas_kd_create($id, $val);
            } else {
                kd_tugas_kd_delete($check['id']);
            }
        }

        $CI->session->set_flashdata('kd', get_alert('success', 'Analisis berhasil diperbaharui'));
        redirect('plugins/pencapaian_kd/edit/' . $id . '/' . enurl_redirect($uri_back));
    }

    $format_data = kd_tugas_format_item($kd_tugas);
    $data['r']   = array_merge($kd_tugas, $format_data);
    $data['tugas_ganda'] = kd_tugas_ganda_retrieve_all();

    $CI->twig->display('kd-edit-tugas.html', $data);
}

function kd_no_soal($id)
{
    $CI =& get_instance();

    $kd_tugas = kd_tugas_retrieve($id);
    if (empty($kd_tugas)) { die; }

    $format_data = kd_tugas_format_item($kd_tugas);
    $data['r']   = array_merge($kd_tugas, $format_data);

    # ambil semua pertanyaan dan pilihan
    $pertanyaan = $CI->tugas_model->retrieve_all_pertanyaan('all', 1, $data['r']['tugas']['id']);
    foreach ($pertanyaan as $key => $val) {
        $val['pilihan'] = $CI->tugas_model->retrieve_all_pilihan($val['id']);
        $pertanyaan[$key] = $val;
    }
    $data['pertanyaan'] = $pertanyaan;

    $CI->twig->display('kd-no-soal.html', $data);
}

function hasil_data($id, $tipe = "")
{
    $CI =& get_instance();

    $kd_tugas = kd_tugas_retrieve($id);
    if (empty($kd_tugas)) { die; }

    $format_data = kd_tugas_format_item($kd_tugas);
    $data['r']   = array_merge($kd_tugas, $format_data);

    // pr($data);die; 

    # cari kelas yang sudah mengerjakan
    $kelas_nilai = array();

    # ambil nilai
    $retrieve_nilai = $CI->tugas_model->retrieve_all_nilai($kd_tugas['tugas_id']);
    foreach ($retrieve_nilai as $nilai) {
        # cari siswa
        $siswa = $CI->siswa_model->retrieve($nilai['siswa_id']);

        # kelas siswa
        $kelas_siswa = $CI->kelas_model->retrieve_siswa(null, array(
            'siswa_id' => $nilai['siswa_id'],
            'aktif'    => 1
        ));
        $kelas = $CI->kelas_model->retrieve($kelas_siswa['kelas_id']);

        if (!isset($kelas_nilai[$kelas['id']])) {
            $kelas_nilai[$kelas['id']] = $kelas;
        }
    }

    $data['kelas_nilai'] = $kelas_nilai;

    # jika tipe tidak kosong
    if (!empty($tipe)) {
        if ($tipe == 'analisis_kd') {

            # ambil pertanyaan
            $arr_kunci  = array();
            $arr_pertanyaan_id = array();
            $pertanyaan = $CI->tugas_model->retrieve_all_pertanyaan('all', 1, $data['r']['tugas']['id']);
            foreach ($pertanyaan as $key => $val) {
                $arr_pertanyaan_id[] = $val['id'];

                # cari kunci
                $pilihan = $CI->tugas_model->retrieve_all_pilihan($val['id']);
                foreach ($pilihan as $key2 => $val2) {
                    if ($val2['kunci'] == 1) {
                        $arr_kunci[$val['id']] = $val2['id'];
                    }
                }
            }
            $data['list_pertanyaan_id'] = $arr_pertanyaan_id;
            $data['list_kunci'] = $arr_kunci;

            $arr_pertanyaan_id_kd_tugas_kd = array();
            foreach ($data['r']['kd_tugas_kd'] as $key => $val) {
                if (!empty($val['no_soal_arr']['pertanyaan_id'])) {
                    foreach ($val['no_soal_arr']['pertanyaan_id'] as $key2 => $val2) {
                        $arr_pertanyaan_id_kd_tugas_kd[$val2] = $val['kd_mapel']['nama'];
                    }
                }
            }
            $data['list_kd_pertanyaan_id'] = $arr_pertanyaan_id_kd_tugas_kd;

            # cari jumlah yang jawab bener semua kelas
            $jml_perkelas = array();
            $data_nilai = array();
            foreach ($retrieve_nilai as $nilai) {
                # cari history
                $history_id = 'history-mengerjakan-' . $nilai['siswa_id'] . '-' . $data['r']['tugas']['id'];
                $history    = retrieve_field($history_id);

                # jika history kosong
                if (empty($history)) {
                    continue;
                }

                $nilai['history'] = $history;
                $nilai['history']['value'] = json_decode($history['value'], 1);

                # kelas siswa
                $kelas_siswa = $CI->kelas_model->retrieve_siswa(null, array(
                    'siswa_id' => $nilai['siswa_id'],
                    'aktif'    => 1
                ));
                $kelas = $CI->kelas_model->retrieve($kelas_siswa['kelas_id']);

                if (!empty($nilai['history']['value']['jawaban'])) {
                    foreach ($nilai['history']['value']['jawaban'] as $key => $val) {
                        # cek bener tidak
                        if ($arr_kunci[$key] == $val) {
                            if (isset($jml_perkelas[$kelas['id']][$key])) {
                                $jml_perkelas[$kelas['id']][$key] = $jml_perkelas[$kelas['id']][$key] + 1;
                            } else {
                                $jml_perkelas[$kelas['id']][$key] = 1;
                            }
                        }
                    }
                }
            }
            $data['list_perkelas'] = $jml_perkelas;

            // pr($data);die;

            if (!empty($_GET['mode']) AND $_GET['mode'] == 'excel') {
                $data['mode'] = $_GET['mode'];

                header("Content-type: application/vnd-ms-excel");
                header("Content-Disposition: attachment; filename=hasil-analisis-semuakelas-" . $data['r']['id']  . ".xls");
            }

            $CI->twig->display('kd-hasil-data-analisis.html', $data);
        } else {
            if (empty($kelas_nilai[$tipe])) { die; }

            $data['kelas_terpilih'] = $kelas_nilai[$tipe];

            # ambil pertanyaan dan kunci
            $arr_kunci  = array();
            $pertanyaan = $CI->tugas_model->retrieve_all_pertanyaan('all', 1, $data['r']['tugas']['id']);
            foreach ($pertanyaan as $key => $val) {
                $pilihan = $CI->tugas_model->retrieve_all_pilihan($val['id']);
                foreach ($pilihan as $key2 => $val2) {
                    if ($val2['kunci'] == 1) {
                        $arr_kunci[$val['id']] = $val2['urutan'];
                    }
                }
            }

            $data['list_kunci'] = $arr_kunci;

            # cari jawaban siswa
            $data_nilai = array();
            foreach ($retrieve_nilai as $nilai) {
                # cari history
                $history_id = 'history-mengerjakan-' . $nilai['siswa_id'] . '-' . $data['r']['tugas']['id'];
                $history    = retrieve_field($history_id);

                # jika history kosong
                if (empty($history)) {
                    continue;
                }

                $nilai['history'] = $history;
                $nilai['history']['value'] = json_decode($history['value'], 1);

                # cari siswa
                $siswa = $CI->siswa_model->retrieve($nilai['siswa_id']);

                # kelas siswa
                $kelas_siswa = $CI->kelas_model->retrieve_siswa(null, array(
                    'siswa_id' => $nilai['siswa_id'],
                    'aktif'    => 1
                ));
                $kelas = $CI->kelas_model->retrieve($kelas_siswa['kelas_id']);
                $siswa['kelas_aktif'] = $kelas;

                $nilai['siswa'] = $siswa;

                if ($kelas['id'] == $data['kelas_terpilih']['id']) {
                    $data_nilai[] = $nilai;
                }
            }

            $data['data_nilai'] = $data_nilai;

            if (!empty($_GET['mode']) AND $_GET['mode'] == 'excel') {
                $data['mode'] = $_GET['mode'];

                header("Content-type: application/vnd-ms-excel");
                header("Content-Disposition: attachment; filename=hasil-analisis-" . url_title($data['kelas_terpilih']['nama'], '-', true)  . ".xls");
            }

            $CI->twig->display('kd-hasil-data-perkelas.html', $data);
        }
    } else {
        $CI->twig->display('kd-hasil-data.html', $data);
    }
}

function delete($id)
{
    $CI =& get_instance();

    $kd_tugas = kd_tugas_retrieve($id);
    if (empty($kd_tugas)) { die; }

    kd_tugas_kd_delete_by_tugas($kd_tugas['id']);

    kd_tugas_delete($kd_tugas['id']);

    $CI->session->set_flashdata('kd', get_alert('success', 'Analisis berhasil dihapus.'));
    redirect('plugins/pencapaian_kd');
}

function ajax($act)
{
    $CI =& get_instance();

    switch ($act) {
        case 'on_select_tugas_id':
            $tugas_id = $CI->input->post('tugas_id', true);
            if (empty($tugas_id)) { die; }

            $tugas = $CI->tugas_model->retrieve($tugas_id);
            $tugas = kd_tugas_format($tugas);

            $data['kd_mapel'] = kd_mapel_retrieve_all($tugas['mapel_id']);

            $data['tugas'] = $tugas;
            $CI->twig->display('kd-list-kd-mapel.html', $data);
        break;

        case 'form_list_kd_mapel':
            $tugas_id = $CI->input->post('tugas_id', true);
            if (empty($tugas_id)) { die; }

            $default_checked = $CI->input->post('default_checked', true);
            if (!empty($default_checked)) {
                $default_checked = explode(", ", $default_checked);
                $data['default_checked'] = $default_checked;
            }

            $tugas = $CI->tugas_model->retrieve($tugas_id);

            $data['kd_mapel'] = kd_mapel_retrieve_all($tugas['mapel_id']);
            $CI->twig->display('kd-form-list-kd-mapel.html', $data);
        break;

        case 'add_kd_mapel':
            $tugas_id = $CI->input->post('tugas_id', true);
            if (empty($tugas_id)) { die; }

            $nama = $CI->input->post('nama', true);
            if (empty($nama)) { die; }

            $tugas = $CI->tugas_model->retrieve($tugas_id);

            $result = kd_mapel_add($tugas['mapel_id'], $nama);

            echo $result;
        break;

        case 'edit_kd_mapel':
            $id = $CI->input->post('id', true);
            if (empty($id)) { die; }

            $nama = $CI->input->post('nama', true);
            if (empty($nama)) { die; }

            kd_mapel_edit($id, $nama);

            echo "1";
        break;

        case 'delete_kd_mapel':
            $id = $CI->input->post('id', true);
            if (empty($id)) { die; }

            kd_mapel_delete($id);

            echo "1";
        break;

        case 'set_kd_no_soal':
            $id = $CI->input->post('id', true);
            if (empty($id)) { die; }

            $pertanyaan_id = $CI->input->post('pertanyaan_id', true);
            if (empty($pertanyaan_id)) { die; }

            $kd_tugas_kd = kd_tugas_kd_retrieve_by_id($id);
            if (empty($kd_tugas_kd)) { echo "0";die; }

            $val = json_decode($kd_tugas_kd['no_soal'], 1);
            if (empty($val['pertanyaan_id'])) {
                kd_tugas_kd_update($kd_tugas_kd['id'], json_encode(array(
                    'pertanyaan_id' => array($pertanyaan_id)
                )));
            } else {
                if (!in_array($pertanyaan_id, $val['pertanyaan_id'])) {
                    $val['pertanyaan_id'][] = $pertanyaan_id;
                    kd_tugas_kd_update($kd_tugas_kd['id'], json_encode(array(
                        'pertanyaan_id' => $val['pertanyaan_id']
                    )));
                }
            }

            # cari nomor soal di kd yang lain
            $CI->db->where('id !=', $kd_tugas_kd['id']);
            $CI->db->where('kd_tugas_id', $kd_tugas_kd['kd_tugas_id']);
            $results = $CI->db->get('kd_tugas_kd');
            foreach ($results->result_array() as $key => $value) {
                $val = json_decode($value['no_soal'], 1);

                if (isset($val['pertanyaan_id']) AND in_array($pertanyaan_id, $val['pertanyaan_id'])) {
                    $find_key = array_search($pertanyaan_id, $val['pertanyaan_id']);
                    unset($val['pertanyaan_id'][$find_key]);

                    kd_tugas_kd_update($value['id'], json_encode(array(
                        'pertanyaan_id' => $val['pertanyaan_id']
                    )));
                }
            }

            echo "1";
        break;
    }
}
