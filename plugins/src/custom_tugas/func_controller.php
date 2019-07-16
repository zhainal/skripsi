<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function construct()
{
    # cek session
    must_login();

    include 'func_model.php';
}

function ajax($get = "")
{
    $CI =& get_instance();

    if (!is_ajax()) {
        die("Akses ditolak");
    }

    switch ($get) {
        case 'update_ragu':
            $tugas_id      = (int)$CI->input->post('tugas_id', true);
            $pertanyaan_id = (int)$CI->input->post('pertanyaan_id', true);
            $act           = $CI->input->post('act', true);

            $tugas = $CI->tugas_model->retrieve($tugas_id);
            if (empty($tugas)) {
                exit('Akses Ditolak');
            }

            $pertanyaan = $CI->tugas_model->retrieve_pertanyaan($pertanyaan_id);
            if (empty($pertanyaan)) {
                exit('Akses Ditolak');
            }

            $table_name  = 'field_tambahan';
            $field_id    = 'mengerjakan-' . get_sess_data('user', 'id') . '-' . $tugas['id'];
            $field_name  = 'Mengerjakan Tugas';

            $check_field = retrieve_field($field_id);
            if (empty($check_field)) {
                exit('Akses Ditolak');
            }

            $field_value = json_decode($check_field['value'], 1);
            if ($act == "add") {
                if (!isset($field_value['ragu'])) {
                    # update index jawaban
                    $field_value['ragu'][] = $pertanyaan['id'];
                } elseif (!in_array($pertanyaan['id'], $field_value['ragu'])) {
                    # update index jawaban
                    $field_value['ragu'][] = $pertanyaan['id'];
                }
            } elseif ($act == "remove" AND !empty($field_value['ragu'])) {
                # cari index
                $key = array_search($pertanyaan['id'], $field_value['ragu']);

                unset($field_value['ragu'][$key]);
            }

            update_field($field_id, $field_name, json_encode($field_value));
        break;
    }
}

function add($segment_3 = '')
{
    $CI =& get_instance();

    # harus admin atau pengajar
    if (!is_admin() AND !is_pengajar()) {
        $CI->session->set_flashdata('tugas', get_alert('warning', 'Akses Ditolak.'));
        redirect('tugas');
    }

    $type = (string)strtolower($segment_3);
    if (!in_array($type, array(1, 2, 3))) {
        redirect('tugas');
    }

    # type label
    if ($type == 1) {
        $data['type_label'] = 'Upload';
        $form_validation    = 'tugas/add_upload';
    }
    if ($type == 2) {
        $data['type_label'] = 'Essay';
        $form_validation    = 'tugas/add_ganda_essay';
    }
    if ($type == 3) {
        $data['type_label'] = 'Ganda';
        $form_validation    = 'tugas/add_ganda_essay';
    }

    $data['type']    = $type;
    $data['mapel']   = $CI->mapel_model->retrieve_all_mapel();
    $data['kelas']   = $CI->kelas_model->retrieve_all_child();
    $data['comp_js'] = get_texteditor();

    if ($CI->form_validation->run($form_validation) == TRUE) {
        $mapel_id = $CI->input->post('mapel_id', TRUE);
        $judul    = $CI->input->post('judul', TRUE);
        $info     = $CI->input->post('info');
        $durasi   = null;
        if ($type != 1) {
            $durasi = $CI->input->post('durasi', TRUE);
        }

        $tugas_id = $CI->tugas_model->create(
            $mapel_id,
            get_sess_data('user', 'id'),
            $type,
            $judul,
            $durasi,
            $info
        );

        # simpan kelas tugas
        $kelas_id = $CI->input->post('kelas_id', TRUE);
        foreach ($kelas_id as $tugas_kelas_id) {
            $CI->tugas_model->create_kelas($tugas_id, $tugas_kelas_id);
        }

        if ($type != 1) {
            $terbitkan_pada = $CI->input->post('terbitkan_pada', true);
            $tutup_pada = $CI->input->post('tutup_pada', true);
            # validasi format
            if (!empty($terbitkan_pada) && !plugin_helper('custom_tugas', 'ct_validate_datetime', array($terbitkan_pada))) {
                $terbitkan_pada = "";
            }
            if (!empty($tutup_pada) && !plugin_helper('custom_tugas', 'ct_validate_datetime', array($tutup_pada))) {
                $tutup_pada = "";
            }
            # jika valid
            if (!empty($terbitkan_pada) && strtotime($terbitkan_pada) > time()) {
                plugin_helper('custom_tugas', 'ct_cron', array('register_action_terbitkan', array(
                    'tugas_id' => $tugas_id,
                    'date'     => $terbitkan_pada,
                )));
            }
            if (!empty($tutup_pada) && strtotime($tutup_pada) > time()) {
                plugin_helper('custom_tugas', 'ct_cron', array('register_action_tutup', array(
                    'tugas_id' => $tugas_id,
                    'date'     => $tutup_pada,
                )));
            }

            $attr = array(
                'max_jml_soal'           => $CI->input->post('max_jml_soal', true),
                'model_urutan_soal'      => $CI->input->post('model_urutan_soal', true),
                'tampil_soal_perhalaman' => $CI->input->post('tampil_soal_perhalaman', true),
                'tampil_nilai_kesiswa'   => $CI->input->post('tampil_nilai_kesiswa', true),
                'terbitkan_pada'         => $terbitkan_pada,
                'tutup_pada'             => $tutup_pada,
            );

            if ($type == 3) {
                $attr['model_urutan_pilihan'] = $CI->input->post('model_urutan_pilihan', true);
            }

            # simpan additional field tugas
            create_field('pengaturan-tugas-' . $tugas_id, 'Pengaturan Tambahan Tugas', json_encode($attr));

            # redirect ke manajemen soal
            $CI->session->set_flashdata('tugas', get_alert('success', 'Manajemen Soal Tugas.'));
            redirect('tugas/manajemen_soal/' . $tugas_id);
        } else {
            $attr = array(
                'tampil_nilai_kesiswa' => $CI->input->post('tampil_nilai_kesiswa', true),
            );

            # simpan additional field tugas
            create_field('pengaturan-tugas-' . $tugas_id, 'Pengaturan Tambahan Tugas', json_encode($attr));

            $CI->session->set_flashdata('tugas', get_alert('success', 'Tugas Upload Berhasil Disimpan.'));
            redirect('tugas');
        }
    }

    $CI->twig->display('tambah-tugas.html', $data);
}

function edit($segment_3 = '', $segment_4 = '')
{
    $CI =& get_instance();

    # harus admin atau pengajar
    if (!is_admin() AND !is_pengajar()) {
        $CI->session->set_flashdata('tugas', get_alert('warning', 'Akses Ditolak.'));
        redirect('tugas');
    }

    $tugas_id = (int)$segment_3;
    $uri_back = (string)$segment_4;

    if (empty($uri_back)) {
        $uri_back = site_url('tugas');
    } else {
        $uri_back = deurl_redirect($uri_back);
    }

    $data['uri_back'] = $uri_back;

    if (empty($tugas_id)) {
        redirect($uri_back);
    }

    $tugas = $CI->tugas_model->retrieve($tugas_id);
    if (empty($tugas)) {
        redirect($uri_back);
    }

    # jika sebagai pengajar, cek kepemilikan
    if (is_pengajar() AND $tugas['pengajar_id'] != get_sess_data('user', 'id')) {
        redirect('tugas');
    }

    # type label
    if ($tugas['type_id'] == 1) {
        $data['type_label'] = 'Upload';
        $form_validation    = 'tugas/add_upload';
    }
    if ($tugas['type_id'] == 2) {
        $data['type_label'] = 'Essay';
        $form_validation    = 'tugas/add_ganda_essay';
    }
    if ($tugas['type_id'] == 3) {
        $data['type_label'] = 'Ganda';
        $form_validation    = 'tugas/add_ganda_essay';
    }

    # hanya ambil kelas_idnya
    $tugas_kelas    = $CI->tugas_model->retrieve_all_kelas($tugas['id']);
    $tugas_kelas_id = array();
    foreach ($tugas_kelas as $r) {
        $tugas_kelas_id[] = $r['kelas_id'];
    }

    $tugas['max_jml_soal']      = 0;
    $tugas['model_urutan_soal'] = 1;

    if ($tugas['type_id'] == 3) {
        $tugas['model_urutan_pilihan'] = 2;
    }

    $tugas['tampil_soal_perhalaman'] = '0';
    # cari pengaturan tambahan tugas
    $pengaturan_tugas = retrieve_field('pengaturan-tugas-' . $tugas['id']);
    if (!empty($pengaturan_tugas['value'])) {
        $value_pengaturan_tugas = json_decode($pengaturan_tugas['value'], 1);
        $tugas = array_merge($tugas, $value_pengaturan_tugas);
    }

    $data['tugas']       = $tugas;
    $data['tugas_kelas'] = $tugas_kelas_id;
    $data['mapel']       = $CI->mapel_model->retrieve_all_mapel();
    $data['kelas']       = $CI->kelas_model->retrieve_all_child();
    $data['comp_js']     = get_texteditor();

    if ($CI->form_validation->run($form_validation) == TRUE) {
        $mapel_id = $CI->input->post('mapel_id', TRUE);
        $judul    = $CI->input->post('judul', TRUE);
        $info     = $CI->input->post('info');
        $durasi   = null;
        if ($tugas['type_id'] != 1) {
            $durasi = $CI->input->post('durasi', TRUE);
        }

        $CI->tugas_model->update(
            $tugas['id'],
            $mapel_id,
            $tugas['pengajar_id'],
            $tugas['type_id'],
            $judul,
            $durasi,
            $info,
            $tugas['aktif']
        );

        # cari kelas tugas mana yang harus ditambah / dihapus
        $kelas_id      = $CI->input->post('kelas_id', TRUE);
        $kelas_post_id = array();
        foreach ($kelas_id as $post_kelas_id) {
            $post_kelas_id = (int)$post_kelas_id;
            if (!empty($post_kelas_id)) {
                $check = $CI->tugas_model->retrieve_kelas(null, $tugas['id'], $post_kelas_id);
                if (empty($check)) {
                    # tambahkan
                    $CI->tugas_model->create_kelas($tugas['id'], $post_kelas_id);
                }
                $kelas_post_id[] = $post_kelas_id;
            }
        }

        if (count($tugas_kelas_id) > count($kelas_post_id)) {
            $diff_kelas = array_diff($tugas_kelas_id, $kelas_post_id);
            foreach ($diff_kelas as $diff_kelas_id) {
                $retrieve = $CI->tugas_model->retrieve_kelas(null, $tugas['id'], $diff_kelas_id);
                # hapus
                if (!empty($retrieve)) {
                    $CI->tugas_model->delete_kelas($retrieve['id']);
                }
            }
        }

        # simpan pengaturan
        if ($tugas['type_id'] == 1) {
            $attr = array(
                'tampil_nilai_kesiswa' => $CI->input->post('tampil_nilai_kesiswa', true),
            );
        } else {
            $terbitkan_pada = $CI->input->post('terbitkan_pada', true);
            $tutup_pada = $CI->input->post('tutup_pada', true);
            # validasi format
            if (!empty($terbitkan_pada) && !plugin_helper('custom_tugas', 'ct_validate_datetime', array($terbitkan_pada))) {
                $terbitkan_pada = "";
            }
            if (!empty($tutup_pada) && !plugin_helper('custom_tugas', 'ct_validate_datetime', array($tutup_pada))) {
                $tutup_pada = "";
            }

            # jika valid, coba cek sudah lewat dari tgl sekarang belum
            if (!empty($terbitkan_pada) && strtotime($terbitkan_pada) <= time()) {
                $terbitkan_pada = "";
            }
            if (!empty($tutup_pada) && strtotime($tutup_pada) <= time()) {
                $tutup_pada = "";
            }

            plugin_helper('custom_tugas', 'ct_cron', array('register_action_terbitkan', array(
                'tugas_id' => $tugas_id,
                'date'     => $terbitkan_pada,
            )));

            plugin_helper('custom_tugas', 'ct_cron', array('register_action_tutup', array(
                'tugas_id' => $tugas_id,
                'date'     => $tutup_pada,
            )));

            $attr = array(
                'max_jml_soal'           => $CI->input->post('max_jml_soal', true),
                'model_urutan_soal'      => $CI->input->post('model_urutan_soal', true),
                'tampil_soal_perhalaman' => $CI->input->post('tampil_soal_perhalaman', true),
                'tampil_nilai_kesiswa'   => $CI->input->post('tampil_nilai_kesiswa', true),
                'terbitkan_pada'         => $terbitkan_pada,
                'tutup_pada'             => $tutup_pada,
            );
        }

        if ($tugas['type_id'] == 3) {
            $attr['model_urutan_pilihan'] = $CI->input->post('model_urutan_pilihan', true);
        }

        if (empty($pengaturan_tugas)) {
            create_field('pengaturan-tugas-' . $tugas['id'], 'Pengaturan Tambahan Tugas', json_encode($attr));
        } else {
            update_field('pengaturan-tugas-' . $tugas['id'], $pengaturan_tugas['nama'], json_encode($attr));
        }

        $CI->session->set_flashdata('tugas', get_alert('success', 'Tugas Berhasil Diperbaharui.'));
        redirect($uri_back);
    }

    $CI->twig->display('edit-tugas.html', $data);
}

function kerjakan($tugas_id = '', $page_no = '')
{
    $CI =& get_instance();

    if (!is_siswa()) {
        show_error("Anda tidak login sebagai Siswa.");
    }

    $tugas_id = (int)$tugas_id;
    $tugas    = $CI->tugas_model->retrieve($tugas_id);
    if (empty($tugas)) {
        show_error("Tugas Tidak Ditemukan.");
    }

    # cek aktif tidak dan tampil siswa tidak
    if (empty($tugas['aktif'])) {
        show_error("Tugas Belum Aktif.");
    }

    if (empty($tugas['tampil_siswa'])) {
        show_error("Tugas Belum Aktif.");
    }

    # dibuat variabel baru untuk php versi < 5.5
    $sudah_mengerjakan = sudah_ngerjakan($tugas['id'], get_sess_data('user', 'id'));

    # cek sudah mengerjakan belum
    if ($sudah_mengerjakan == true) {
        show_error("Anda sudah mengerjakan tugas ini.");
    }

    # ambil pengaturan tugas
    $tugas['max_jml_soal']           = 0;
    $tugas['model_urutan_soal']      = 1;
    $tugas['tampil_soal_perhalaman'] = '0';
    # cari pengaturan tambahan tugas
    $pengaturan_tugas = retrieve_field('pengaturan-tugas-' . $tugas['id']);
    if (!empty($pengaturan_tugas['value'])) {
        $value_pengaturan_tugas = json_decode($pengaturan_tugas['value'], 1);
        $tugas = array_merge($tugas, $value_pengaturan_tugas);
    }

    $field_id    = 'mengerjakan-' . get_sess_data('user', 'id') . '-' . $tugas['id'];
    $field_name  = 'Mengerjakan Tugas';

    $mulai   = date('Y-m-d H:i:s');
    $durasi  = $tugas['durasi'];
    $selesai = date('Y-m-d H:i:s', strtotime("+$durasi minutes", strtotime($mulai)));

    $field_value = array(
        'mulai'      => $mulai,
        'selesai'    => $selesai,
        'uri_string' => uri_string()
    );

    # untuk keperluan check sedang ujian
    $field_value['valid_route'] = array(
        '/plugins/custom_tugas/kerjakan',
        '/tugas/finish',
        '/tugas/submit_essay',
        '/tugas/submit_upload',
        '/plugins/custom_tugas/finish',
        '/plugins/custom_tugas/submit_essay'
    );

    # simpan tugas dan unix_id nya
    $field_value['tugas']        = $tugas;
    $field_value['unix_id']      = md5($field_id) . rand(9, 999999);
    $field_value['ip']           = get_ip();
    $field_value['agent_string'] = $CI->agent->agent_string();

    # cek sudah pernah mengerjakan belum, untuk keamanan.
    # karna bisa saja dibuka 2 kali dikomputer yang berbeda
    $check_field = retrieve_field($field_id);
    if (!empty($check_field)) {
        $check_field_value = json_decode($check_field['value'], 1);

        # cek upload tidak dan sudah selesai belum dari segi waktunya
        if ($tugas['type_id'] != 1 AND strtotime($mulai) >= strtotime($check_field_value['selesai'])) {
            redirect('tugas/finish/' . $tugas['id'] . '/' . $check_field_value['unix_id']);
        }
    }

    # jika masih kosong, berarti belum mengerjakan sama sekali
    else {
        $pertanyaan = array();
        if ($tugas['type_id'] != 1) {
            # ambil pertanyaan ditugas ini
            $pertanyaan    = ct_retrieve_all_pertanyaan('all', 1, $tugas['id'], ($tugas['model_urutan_soal'] == 1) ? 'random' : 'asc');
            $pertanyaan_id = array();
            $l = 1;
            foreach ($pertanyaan as $key => $val) {
                # continue saja kalo sudah lebih dari max_jml_soal
                if ($tugas['max_jml_soal'] > 0 AND count($pertanyaan_id) == $tugas['max_jml_soal']) {
                    continue;
                }
                $pertanyaan_id[$l] = $val['id'];
                $l++;
            }

            # jika pertanyaan masih kosong
            if (empty($pertanyaan_id)) {
                $CI->session->set_flashdata('tugas', get_alert('warning', 'Pertanyaan tugas masih kosong.'));
                redirect('tugas');
            }

            $field_value['pertanyaan_id'] = $pertanyaan_id;
        } else {
            unset($field_value['selesai']);
        }

        # start transaksi
        $CI->db->trans_start();
        # simpan
        create_field($field_id, $field_name, json_encode($field_value));

        $CI->db->trans_complete();
        if ($CI->db->trans_status() === FALSE) {
            show_error("Proses simpan field gagal.");
        }
    }

    $check_field       = retrieve_field($field_id);
    $check_field_value = json_decode($check_field['value'], 1);

    # ini untuk mendapatkan data soal lengkapnya
    $p_id_tampil = array();
    $soal = array();

    # kapan button submit ditampilkan
    $data['tampil_btn_submit'] = 1;

    # jika ditampilkan semua
    $data['pagination'] = '';
    if ($tugas['tampil_soal_perhalaman'] == 0) {

        # jika essay
        if ($tugas['type_id'] == 2) {
            foreach ($check_field_value['pertanyaan_id'] as $no => $p_id) {
                $pertanyaan    = $CI->tugas_model->retrieve_pertanyaan($p_id);
                $soal[$no]     = $pertanyaan;
                $p_id_tampil[] = $pertanyaan['id'];
            }
        }

        # jika ganda
        if ($tugas['type_id'] == 3) {
            foreach ($check_field_value['pertanyaan_id'] as $no => $p_id) {
                $pertanyaan = $CI->tugas_model->retrieve_pertanyaan($p_id);

                # jika pilihan belum pernah disimpan
                if (empty($check_field_value['pertanyaan_pilihan_id'][$pertanyaan['id']])) {
                    # ambil data pilihan
                    $pertanyaan['pilihan'] = $CI->tugas_model->retrieve_all_pilihan($pertanyaan['id'], (!isset($tugas['model_urutan_pilihan']) OR $tugas['model_urutan_pilihan'] == 2) ? 'asc' : 'random');

                    # ini untuk kebutuhan disimpan
                    foreach ($pertanyaan['pilihan'] as $row_pil) {
                        $check_field_value[$pertanyaan['id']][] = $row_pil['id'];
                        $please_simpan_pilihan_id = 1;
                    }
                } else {
                    foreach ($check_field_value['pertanyaan_pilihan_id'][$pertanyaan['id']] as $pil_id) {
                        $pertanyaan['pilihan'][] = $CI->tugas_model->retrieve_pilihan($pil_id);
                    }
                }

                $soal[$no]     = $pertanyaan;
                $p_id_tampil[] = $pertanyaan['id'];
            }
        }

    }

    # jika dengan pagination
    else {

        $retrieve_all = ct_retrieve_all_pertanyaan($tugas['tampil_soal_perhalaman'], empty($page_no) ? 1 : (int)$page_no, $tugas['id'], 'ASC', $check_field_value['pertanyaan_id']);

        # jika essay
        if ($tugas['type_id'] == 2) {
            foreach ($retrieve_all['results'] as $no => $row) {
                $soal[$no]     = $row;
                $p_id_tampil[] = $row['id'];
            }
        }

        # jika pilihan ganda
        if ($tugas['type_id'] == 3) {
            foreach ($retrieve_all['results'] as $no => $row) {

                # jika pilihan belum pernah disimpan
                if (empty($check_field_value['pertanyaan_pilihan_id'][$row['id']])) {
                    # ambil data pilihan
                    $row['pilihan'] = $CI->tugas_model->retrieve_all_pilihan($row['id'], (!isset($tugas['model_urutan_pilihan']) OR $tugas['model_urutan_pilihan'] == 2) ? 'asc' : 'random');

                    # ini untuk kebutuhan disimpan
                    foreach ($row['pilihan'] as $row_pil) {
                        $check_field_value['pertanyaan_pilihan_id'][$row['id']][] = $row_pil['id'];
                        $please_simpan_pilihan_id = 1;
                    }
                } else {
                    foreach ($check_field_value['pertanyaan_pilihan_id'][$row['id']] as $pil_id) {
                        $row['pilihan'][] = $CI->tugas_model->retrieve_pilihan($pil_id);
                    }
                }

                $soal[$no]     = $row;
                $p_id_tampil[] = $row['id'];
            }
        }

        $data['pagination'] = $CI->pager->view($retrieve_all, 'plugins/custom_tugas/kerjakan/'.$tugas['id'].'/');

        # sembunyikan button submit jika belum halaman terahir
        $halaman_saat_ini = empty($page_no) ? 1 : (int)$page_no;
        if ($halaman_saat_ini < $retrieve_all['total_page']) {
            $data['tampil_btn_submit'] = 0;
        }
    }

    # jika ada please_simpan_pilihan_id berarti ada perintah untuk simpan pilihan id
    if (!empty($please_simpan_pilihan_id)) {
        # start transaksi
        $CI->db->trans_start();
        # update
        update_field($field_id, $field_name, json_encode($check_field_value));

        $CI->db->trans_complete();
        if ($CI->db->trans_status() === FALSE) {
            show_error("Proses simpan pilihan field Gagal.");
        }
    }

    $check_field_value['pertanyaan'] = $soal;

    if ($tugas['type_id'] != 1) {
        # cari sisa waktu dalam menit
        $sisa_menit = (strtotime($check_field_value['selesai']) - strtotime($mulai));
        $check_field_value['sisa_menit'] = ceil($sisa_menit);
    }

    # save data
    $data['data'] = $check_field_value;
    $html_js      = '';
    $html_css     = '';
    if ($tugas['type_id'] != 1) {
        $html_js .= load_comp_js(array(
            base_url('assets/comp/jcounter/js/jquery.jCounter-0.1.4.js'),
            base_url('assets/comp/jquery/ujian.js'),
            base_url_plugins('src/custom_tugas/js/custom_tugas.js'),
        ));

        $html_css .= load_comp_css(array(
            base_url('assets/comp/jcounter/css/jquery.jCounter-iosl.css'),
        ));
    }

    if ($tugas['type_id'] == 2) {
        $html_js .= get_texteditor();
        $data['data']['str_id'] = implode(',', $p_id_tampil);
    }

    $data['comp_js']  = $html_js;
    $data['comp_css'] = $html_css;

    # jika bukan tugas upload
    if ($tugas['type_id'] != 1) {
        # jika tampil soal perhalaman 0
        if ($tugas['tampil_soal_perhalaman'] == 0) {
            foreach ($data['data']['pertanyaan_id'] as $no => $p_id) {
                $data['halaman'][$p_id] = 1;
            }
        } else {
            $halaman = 1;
            $loop    = 1;
            foreach ($data['data']['pertanyaan_id'] as $no => $p_id) {
                if ($loop > $tugas['tampil_soal_perhalaman']) {
                    $halaman++;
                    $loop = 1;
                }

                $data['halaman'][$p_id] = $halaman;

                $loop++;
            }
        }
    }

    $CI->twig->display('ujian-online.html', $data);
}

function finish($tugas_id = '', $unix_id = '')
{
    $CI =& get_instance();

    if (!is_siswa()) {
        show_error("Anda tidak login sebagai Siswa.");
    }

    $tugas_id = (int)$tugas_id;
    $tugas    = $CI->tugas_model->retrieve($tugas_id);
    if (empty($tugas) OR $tugas['type_id'] == 1) {
        show_error("Tugas Tidak Ditemukan.");
    }

    if (empty($unix_id)) {
        show_error("Parameter Unix ID dibutuhkan.");
    }

    # dibuat variabel baru untuk php versi < 5.5
    $sudah_mengerjakan = sudah_ngerjakan($tugas['id'], get_sess_data('user', 'id'));

    # cek sudah mengerjakan belum
    if ($sudah_mengerjakan == true) {
        show_error("Anda sudah mengerjakan tugas ini.");
    }

    $field_id    = 'mengerjakan-' . get_sess_data('user', 'id') . '-' . $tugas['id'];
    $check_field = retrieve_field($field_id);

    if (!empty($check_field)) {
        # bandingkan unix_id nya
        $check_field_value = json_decode($check_field['value'], 1);
        if ($unix_id != $check_field_value['unix_id']) {
            show_error("Anda tidak mengerjakan tugas ini.");
        }

        # jika pilihan ganda langsung di hitung benar salahnya
        if ($tugas['type_id'] == 3) {
            # kondisi untuk versi tugas yang terlanjur dibuat di versi < 1.5
            if (!isset($check_field_value['pertanyaan_id']) AND isset($check_field_value['pertanyaan'])) {
                $check_field_value['pertanyaan_id'] = array();
                foreach ($check_field_value['pertanyaan'] as $key => $p) {
                    $check_field_value[$key] = $p['id'];
                }

                unset($check_field_value['pertanyaan']);
            }

            $jml_soal = count($check_field_value['pertanyaan_id']);

            # cari kunci jawaban
            $data_kunci = array();
            foreach ($check_field_value['pertanyaan_id'] as $p_id) {
                foreach ($CI->tugas_model->retrieve_all_pilihan($p_id) as $pilihan) {
                    if ($pilihan['kunci'] == 1) {
                        $data_kunci[$p_id] = $pilihan['id'];
                    }
                }
            }

            $jml_benar = 0;
            $jml_salah = 0;

            # cari jawabannya
            if (!empty($check_field_value['jawaban'])) {
                foreach ($check_field_value['jawaban'] as $pertanyaan_id => $pilihan_id) {
                    # cek jawaban benar tidak
                    if (isset($data_kunci[$pertanyaan_id]) && $data_kunci[$pertanyaan_id] == $pilihan_id) {
                        $jml_benar++;
                    } else {
                        $jml_salah++;
                    }
                }

                $nilai = ($jml_benar / $jml_soal) * 100;
            } else {
                $jml_benar = 0;
                $jml_salah = 0;
                $nilai     = 0;
            }

            # start transaksi
            $CI->db->trans_start();

            # simpan nilai
            $CI->tugas_model->create_nilai($nilai, $tugas['id'], get_sess_data('user', 'id'));

            # hapus field tambahan
            delete_field($field_id);

            # simpan history
            $new_field_id                     = 'history-mengerjakan-' . get_sess_data('user', 'id') . '-' . $tugas['id'];
            $check_field_value['nilai']       = $nilai;
            $check_field_value['jml_benar']   = $jml_benar;
            $check_field_value['jml_salah']   = $jml_salah;

            $sekarang                          = date('Y-m-d H:i:s');
            $check_field_value['tgl_submit']   = $sekarang;
            $check_field_value['total_waktu']  = lama_pengerjaan($check_field_value['mulai'], $sekarang);
            $check_field_value['ip']           = get_ip();
            $check_field_value['agent_string'] = $CI->agent->agent_string();

            create_field($new_field_id, 'History Pengerjaan Tugas', json_encode($check_field_value));

            $CI->db->trans_complete();

            if ($CI->db->trans_status() === FALSE) {
                show_error("Proses simpan jawaban gagal, mohon coba submit kembali.");
            }
        }

        # jika essay dan upload, biar dikoreksi dl
        else {
            # start transaksi
            $CI->db->trans_start();

            # hapus field tambahan
            delete_field($field_id);

            # simpan history
            $new_field_id                      = 'history-mengerjakan-' . get_sess_data('user', 'id') . '-' . $tugas['id'];
            $sekarang                          = date('Y-m-d H:i:s');
            $check_field_value['tgl_submit']   = $sekarang;
            $check_field_value['total_waktu']  = lama_pengerjaan($check_field_value['mulai'], $sekarang);
            $check_field_value['ip']           = get_ip();
            $check_field_value['agent_string'] = $CI->agent->agent_string();

            create_field($new_field_id, 'History Pengerjaan Tugas', json_encode($check_field_value));

            $CI->db->trans_complete();

            if ($CI->db->trans_status() === FALSE) {
                show_error("Proses simpan jawaban gagal, mohon coba submit kembali.");
            }
        }

        $CI->session->set_flashdata('tugas', get_alert('success', 'Anda telah berhasil mengerjakan tugas ini.'));

        $CI->twig->display('redirect.html', array('redirect_to' => site_url('tugas')));
    }
    # ini belum mengerjakan
    else {
        show_error("Anda belum mengerjakan tugas ini.");
    }
}

function submit_essay($tugas_id = '', $unix_id = '')
{
    $CI =& get_instance();

    if (!is_siswa()) {
        show_error("Anda tidak login sebagai Siswa.");
    }

    $tugas_id = (int)$tugas_id;
    $tugas    = $CI->tugas_model->retrieve($tugas_id);
    if (empty($tugas) OR $tugas['type_id'] != 2) {
        show_error("Tugas tidak ditemukan.");
    }

    if (empty($unix_id)) {
        show_error("Parameter Unix ID dibutuhkan.");
    }

    # dibuat variabel baru untuk php versi < 5.5
    $sudah_mengerjakan = sudah_ngerjakan($tugas['id'], get_sess_data('user', 'id'));

    # cek sudah mengerjakan belum
    if ($sudah_mengerjakan == true) {
        show_error("Anda sudah mengerjakan tugas ini.");
    }

    $field_id    = 'mengerjakan-' . get_sess_data('user', 'id') . '-' . $tugas['id'];
    $check_field = retrieve_field($field_id);

    if (!empty($check_field)) {
        # bandingkan unix_id nya
        $check_field_value = json_decode($check_field['value'], 1);
        if ($unix_id != $check_field_value['unix_id']) {
            $CI->session->set_flashdata('tugas', get_alert('warning', 'Anda tidak mengerjakan tugas ini.'));
            redirect('tugas');
        }

        # kondisi untuk versi tugas yang terlanjur dibuat di versi < 1.5
        if (!isset($check_field_value['pertanyaan_id']) AND isset($check_field_value['pertanyaan'])) {
            $check_field_value['pertanyaan_id'] = array();
            foreach ($check_field_value['pertanyaan'] as $key => $p) {
                $check_field_value[$key] = $p['id'];
            }

            unset($check_field_value['pertanyaan']);
        }

        $post_jawaban = $CI->input->post('jawaban');
        foreach ($post_jawaban as $pertanyaan_id => $jawaban) {
            # replace yang sudah terimpan atau yang belum disimpan
            $check_field_value['jawaban'][$pertanyaan_id] = $jawaban;
        }

        # start transaksi
        $CI->db->trans_start();

        # hapus field tambahan
        delete_field($field_id);

        # simpan history
        $new_field_id                      = 'history-mengerjakan-' . get_sess_data('user', 'id') . '-' . $tugas['id'];
        $sekarang                          = date('Y-m-d H:i:s');
        $check_field_value['tgl_submit']   = $sekarang;
        $check_field_value['total_waktu']  = lama_pengerjaan($check_field_value['mulai'], $sekarang);
        $check_field_value['ip']           = get_ip();
        $check_field_value['agent_string'] = $CI->agent->agent_string();

        create_field($new_field_id, 'History Pengerjaan Tugas', json_encode($check_field_value));

        $CI->db->trans_complete();

        if ($CI->db->trans_status() === FALSE) {
            show_error("Proses simpan jawaban gagal, mohon coba submit kembali.");
        }

        $CI->session->set_flashdata('tugas', get_alert('success', 'Anda telah berhasil mengerjakan tugas ini.'));

        $CI->twig->display('redirect.html', array('redirect_to' => site_url('tugas')));
    }
    # ini belum mengerjakan
    else {
        show_error("Anda belum mengerjakan tugas ini.");
    }
}
