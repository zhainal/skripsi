<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function construct()
{
    # cek session
    must_login();

    # include model
    include 'func_model.php';

    tk_table_create();
}

function index($param1 = "")
{
    $CI =& get_instance();

    if (!empty($_GET['clear_filter']) AND $_GET['clear_filter'] == 'true') {
        $CI->session->set_userdata('filter_tugas_kelompok', null);
    }

    $page_no = (int)$param1;
    if (empty($page_no)) {
        $page_no = 1;
    }

    $CI->form_validation->set_rules('judul', 'Judul', 'trim');
    $CI->form_validation->set_rules('mapel_id[]', 'Matapelajaran', '');
    $CI->form_validation->set_rules('kelas_id[]', 'Kelas', '');
    $CI->form_validation->set_rules('status[]', 'Status', '');
    $CI->form_validation->set_rules('pembuat', 'Pembuat', 'trim');
    if ($CI->form_validation->run() == true) {
        $pembuat = $CI->input->post('pembuat', TRUE);

        # cari id pengajar
        $pengajar_id = array();
        if (!empty($pembuat)) {
            foreach ($CI->pengajar_model->retrieve_all_by_name($pembuat) as $val) {
                $pengajar_id[] = $val['id'];
            }

            if (empty($pengajar_id)) {
                $pengajar_id[] = 0;
            }
        }

        $filter = array(
            'judul'       => $CI->input->post('judul', true),
            'mapel_id'    => $CI->input->post('mapel_id', true),
            'pengajar_id' => $pengajar_id,
            'pembuat'     => $pembuat,
            'status'      => $CI->input->post('status', true),
            'kelas_id'    => $CI->input->post('kelas_id', true),
        );

        $CI->session->set_userdata('filter_tugas_kelompok', $filter);
    }

    $filter = $CI->session->userdata('filter_tugas_kelompok');
    if (empty($filter)) {
        $filter = array(
            'judul'       => '',
            'pengajar_id' => array(),
            'pembuat'     => '',
            'mapel_id'    => array(),
            'kelas_id'    => array(),
            'status'      => array()
        );
    }

    if (is_pengajar()) {
        $filter['pengajar_id'] = array(get_sess_data('user', 'id'));
    }

    if (is_siswa()) {
        $filter['kelas_id'] = array($CI->siswa_kelas_aktif['kelas_id']);
    }

    $data['filter'] = $filter;

    # ambil semua data tugas
    $retrieve_all_tugas = tk_tugas(
        'retrieve_all',
        array(
            'no_of_records' => 20,
            'page_no'       => $page_no,
            'judul'         => $filter['judul'],
            'mapel_id'      => $filter['mapel_id'],
            'kelas_id'      => $filter['kelas_id'],
            'status'        => $filter['status'],
            'pengajar_id'   => $filter['pengajar_id'],
        )
    );

    $data['tugas']      = $retrieve_all_tugas['results'];
    $data['pagination'] = $CI->pager->view($retrieve_all_tugas, 'plugins/tugas_kelompok/index/');
    $data['kelas']      = $CI->kelas_model->retrieve_all_child();
    $data['mapel']      = $CI->mapel_model->retrieve_all_mapel();

    # panggil colorbox
    $html_js = load_comp_js(array(
        base_url('assets/comp/colorbox/jquery.colorbox-min.js'),
    ));
    $data['comp_js']  = $html_js;
    $data['comp_css'] = load_comp_css(array(base_url('assets/comp/colorbox/colorbox.css')));

    $CI->twig->display('tk-list-tugas.html', $data);
}

function add()
{
    $CI =& get_instance();
    if (is_siswa()) {
        show_error("Akses Ditolak!");
    }

    $CI->form_validation->set_rules('judul', 'Judul', 'trim|required');
    $CI->form_validation->set_rules('mapel_id', 'Mata Pelajaran', 'trim|required');
    $CI->form_validation->set_rules('kelas_id', 'Kelas', 'trim|required');
    if ($CI->form_validation->run() == TRUE) {
        $tugas_id = tk_tugas('insert', array(
            'judul'       => $CI->input->post('judul', true),
            'mapel_id'    => $CI->input->post('mapel_id', true),
            'kelas_id'    => $CI->input->post('kelas_id', true),
            'status'      => 1,
            'pengajar_id' => get_sess_data('user', 'id'),
        ));

        $CI->session->set_flashdata('tugas', get_alert('success', 'Tugas kelompok berhasil dibuat, silahkan atur kelompok.'));
        redirect('plugins/tugas_kelompok/kelompok/' . $tugas_id);
    }

    $data['mapel']   = $CI->mapel_model->retrieve_all_mapel();
    $data['kelas']   = $CI->kelas_model->retrieve_all_child();

    $CI->twig->display('tk-tambah-tugas.html', $data);
}

function edit($param1 = "", $param2 = "")
{
    $CI =& get_instance();
    if (is_siswa()) {
        show_error("Akses Ditolak!");
    }

    $tugas_id = (int)$param1;
    $uri_back = (string)$param2;

    if (empty($uri_back)) {
        $uri_back = site_url('plugins/tugas_kelompok');
    } else {
        $uri_back = deurl_redirect($uri_back);
    }

    $data['uri_back'] = $uri_back;

    if (empty($tugas_id)) {
        redirect($uri_back);
    }

    $tugas = tk_tugas('retrieve', $tugas_id);
    if (empty($tugas)) {
        redirect($uri_back);
    }
    $tugas = tk_tugas('format', $tugas);

    # jika sebagai pengajar, cek kepemilikan
    if (is_pengajar() AND $tugas['pengajar_id'] != get_sess_data('user', 'id')) {
        redirect('plugins/tugas_kelompok');
    }

    $data['tugas']   = $tugas;
    $data['mapel']   = $CI->mapel_model->retrieve_all_mapel();

    $CI->form_validation->set_rules('judul', 'Judul', 'trim|required');
    $CI->form_validation->set_rules('mapel_id', 'Mata Pelajaran', 'trim|required');
    if ($CI->form_validation->run() == TRUE) {
        $mapel_id = $CI->input->post('mapel_id', TRUE);
        $judul    = $CI->input->post('judul', TRUE);

        tk_tugas('update', $tugas['id'], array(
            'judul'    => $judul,
            'mapel_id' => $mapel_id,
        ));

        $CI->session->set_flashdata('tugas', get_alert('success', 'Tugas Kelompok Berhasil Diperbaharui.'));
        redirect($uri_back);
    }

    $CI->twig->display('tk-edit-tugas.html', $data);
}

function terbitkan($param1 = "", $param2 = "")
{
    $CI =& get_instance();
    if (is_siswa()) {
        show_error("Akses Ditolak!");
    }

    $tugas_id = (int)$param1;
    $uri_back = (string)$param2;

    if (empty($uri_back)) {
        $uri_back = site_url('plugins/tugas_kelompok');
    } else {
        $uri_back = deurl_redirect($uri_back);
    }

    if (empty($tugas_id)) {
        redirect($uri_back);
    }

    $tugas = tk_tugas('retrieve', $tugas_id);
    if (empty($tugas)) {
        redirect($uri_back);
    }

    # jika sebagai pengajar, cek kepemilikan
    if (is_pengajar() AND $tugas['pengajar_id'] != get_sess_data('user', 'id')) {
        redirect('plugins/tugas_kelompok');
    }

    tk_tugas('update', $tugas['id'], array('status' => 2));

    $CI->session->set_flashdata('tugas', get_alert('success', 'Tugas Kelompok Berhasil Diterbitkan.'));
    redirect($uri_back);
}

function tutup($param1 = "", $param2 = "")
{
    $CI =& get_instance();
    if (is_siswa()) {
        show_error("Akses Ditolak!");
    }

    $tugas_id = (int)$param1;
    $uri_back = (string)$param2;

    if (empty($uri_back)) {
        $uri_back = site_url('plugins/tugas_kelompok');
    } else {
        $uri_back = deurl_redirect($uri_back);
    }

    if (empty($tugas_id)) {
        redirect($uri_back);
    }

    $tugas = tk_tugas('retrieve', $tugas_id);
    if (empty($tugas)) {
        redirect($uri_back);
    }

    # jika sebagai pengajar, cek kepemilikan
    if (is_pengajar() AND $tugas['pengajar_id'] != get_sess_data('user', 'id')) {
        redirect('plugins/tugas_kelompok');
    }

    tk_tugas('update', $tugas['id'], array('status' => 3));

    $CI->session->set_flashdata('tugas', get_alert('success', 'Tugas Kelompok Berhasil Ditutup.'));
    redirect($uri_back);
}

function kelompok($param1 = "", $param2 = "", $param3 = "")
{
    $CI =& get_instance();
    if (is_siswa()) {
        show_error("Akses Ditolak!");
    }

    $tugas = tk_tugas('retrieve', $param1);
    if (empty($tugas)) {
        show_error("Tugas Tidak Ditemukan");
    }

    # jika sebagai pengajar, cek kepemilikan
    if (is_pengajar() AND $tugas['pengajar_id'] != get_sess_data('user', 'id')) {
        redirect('plugins/tugas_kelompok');
    }

    $data['tugas'] = tk_tugas('format', $tugas);

    switch ($param2) {
        case 'edit':
            $kelompok_id = $param3;
            if (empty($kelompok_id)) { show_error("Kelompok ID Dibutuhkan."); }

            $kelompok = tk_kelompok('retrieve', $kelompok_id);
            if (empty($kelompok)) { show_error("Kelompok Tidak Ditemukan."); }
            if ($kelompok['tugas_id'] != $tugas['id']) { show_error("Kelompok Tidak Valid"); }

            $data['kelompok'] = $kelompok;

            $CI->form_validation->set_rules('nama', 'Nama Kelompok', 'required|trim');
            $CI->form_validation->set_rules('intruksi', 'Intruksi / Informasi', 'trim');

            $session_edit = $CI->session->userdata('tk_session_edit');
            if (empty($session_edit[$kelompok['id']])) {
                $CI->form_validation->set_rules('anggota', 'Anggota Kelompok', 'required');
            }

            if ($CI->form_validation->run() == true) {
                $kelompok_id = tk_kelompok('update', $kelompok['id'], array(
                    'nama'     => $CI->input->post('nama'),
                    'intruksi' => $CI->input->post('intruksi'),
                ));

                # yang tidak ada di post dihapus
                $daftar_anggota = tk_anggota('retrieve_all', $kelompok['id']);
                foreach ($daftar_anggota as $key => $val) {
                    if (empty($session_edit[$kelompok['id']][$val['siswa']['id']])) {
                        # hapus anggota
                        tk_anggota('delete', $val['id']);

                        # hapus kerjaan
                        tk_kerjaan('delete', $val['id']);

                        # hapus nilai anggota
                        tk_nilai_anggota('delete', $val['id']);

                        # hapus penilaian anggota
                        tk_penilaian_anggota('delete', $val['id']);
                    }
                }

                # yang belum ada di insert
                foreach ($session_edit[$kelompok['id']] as $siswa) {
                    $check_siswa = tk_anggota('retrieve', $kelompok['id'], $siswa['id']);
                    if (empty($check_siswa)) {
                        $anggota_id = tk_anggota('insert', array(
                            'kelompok_id' => $kelompok['id'],
                            'siswa_id'    => $siswa['id'],
                        ));
                    }
                }

                unset($session_edit[$kelompok['id']]);
                $CI->session->set_userdata('tk_session_edit', $session_edit);

                $CI->session->set_flashdata('tugas', get_alert('success', 'Kelompok Berhasil Diperbaharui.'));
                redirect('plugins/tugas_kelompok/kelompok/' . $tugas['id'] . '/edit/' . $kelompok['id']);
            }

            $comp_js = get_texteditor();
            $comp_js .= load_comp_js(array(
                base_url('plugins/src/tugas_kelompok/comp/autocomplete/jquery.autocomplete.min.js'),
            ));

            $data['comp_js'] = $comp_js;
            $data['comp_css'] = load_comp_css(array(
                base_url('plugins/src/tugas_kelompok/comp/autocomplete/autocomplete.css'),
            ));

            $CI->twig->display('tk-edit-kelompok.html', $data);
        break;

        case 'add':
            $CI->form_validation->set_rules('nama', 'Nama Kelompok', 'required|trim');
            $CI->form_validation->set_rules('intruksi', 'Intruksi / Informasi', 'trim');

            $session_add = $CI->session->userdata('tk_session_add');
            if (empty($session_add[$tugas['id']])) {
                $CI->form_validation->set_rules('anggota', 'Anggota Kelompok', 'required');
            }

            if ($CI->form_validation->run() == true) {
                $kelompok_id = tk_kelompok('insert', array(
                    'tugas_id' => $tugas['id'],
                    'nama'     => $CI->input->post('nama'),
                    'intruksi' => $CI->input->post('intruksi'),
                ));

                foreach ($session_add[$tugas['id']] as $siswa) {
                    # cek sudah ada dikelompok lain belum
                    $check_siswa = tk_anggota('check_siswa', $tugas['id'], $siswa['id']);
                    if (empty($check_siswa)) {
                        $anggota_id = tk_anggota('insert', array(
                            'kelompok_id' => $kelompok_id,
                            'siswa_id'    => $siswa['id'],
                        ));
                    }
                }

                unset($session_add[$tugas['id']]);
                $CI->session->set_userdata('tk_session_add', $session_add);

                $CI->session->set_flashdata('tugas', get_alert('success', 'Kelompok Baru Berhasil Ditambahkan.'));
                redirect('plugins/tugas_kelompok/kelompok/' . $tugas['id']);
            }

            $comp_js = get_texteditor();
            $comp_js .= load_comp_js(array(
                base_url('plugins/src/tugas_kelompok/comp/autocomplete/jquery.autocomplete.min.js'),
            ));

            $data['comp_js'] = $comp_js;
            $data['comp_css'] = load_comp_css(array(
                base_url('plugins/src/tugas_kelompok/comp/autocomplete/autocomplete.css'),
            ));

            $CI->twig->display('tk-tambah-kelompok.html', $data);
        break;

        default:
            $results = tk_kelompok('retrieve_all', $tugas['id']);
            $data['results'] = $results;

            # panggil datatables
            $data['comp_js'] = load_comp_js(array(
                base_url('assets/comp/datatables/jquery.dataTables.js'),
                base_url('assets/comp/datatables/datatable-bootstrap2.js'),
            ));

            $data['comp_css'] = load_comp_css(array(
                base_url('assets/comp/datatables/datatable-bootstrap2.css'),
            ));

            $CI->twig->display('tk-kelompok.html', $data);
        break;
    }
}

function koreksi($param1 = "", $param2 = "")
{
    $CI =& get_instance();

    if (is_siswa()) { show_error("Akses Ditolak"); }

    $tugas_id = $param1;
    $kelompok_id = $param2;
    if (empty($tugas_id) OR empty($kelompok_id)) {
        show_error("Parameter Tidak Lengkap");
    }

    $tugas = tk_tugas('retrieve', $tugas_id);
    if (empty($tugas)) { show_error("Tugas Tidak Ditemukan"); }

    if (is_pengajar() AND $tugas['pengajar_id'] != get_sess_data('user', 'id')) { show_error("Akses Ditolak"); }

    $kelompok = tk_kelompok('retrieve', $kelompok_id);
    if (empty($kelompok)) { show_error('Kelompok Tidak Ditemukan'); }
    $data['kelompok'] = $kelompok;

    $daftar_anggota = tk_anggota('retrieve_all', $kelompok['id']);

    # cari penilaian anggota ini
    foreach ($daftar_anggota as $key => $val) {
        $val['penilaian_anggota'] = tk_penilaian_anggota('retrieve_all', $val['id']);

        # cari nilai anggota ini
        $val['nilai_anggota'] = tk_nilai_anggota('retrieve', array(
            'kelompok_id' => $kelompok['id'],
            'anggota_id'  => $val['id'],
        ));

        $daftar_anggota[$key] = $val;
    }

    $data['daftar_anggota'] = $daftar_anggota;

    # cari list kerjaan
    $list_kerjaan = tk_kerjaan('retrieve_all_by_kelompok', $kelompok['id']);
    $data['list_kerjaan'] = $list_kerjaan;

    $tugas = tk_tugas('format', $tugas);
    $data['tugas'] = $tugas;

    $nilai_kelompok = tk_nilai_kelompok('retrieve', $kelompok['id']);
    $data['nilai_kelompok'] = $nilai_kelompok;

    # handle post request
    if (is_ajax()) {
        # nilai kelompok
        if (!empty($_POST['nilai'])) {
            # jika belum ada nilai
            if (empty($nilai_kelompok)) {
                tk_nilai_kelompok('insert', array(
                    'kelompok_id' => $kelompok['id'],
                    'nilai'       => $CI->input->post('nilai', true),
                    'catatan'     => $CI->input->post('catatan', true),
                ));
            } else {
                tk_nilai_kelompok('update', $nilai_kelompok['id'], array(
                    'nilai'   => $CI->input->post('nilai', true),
                    'catatan' => $CI->input->post('catatan', true),
                ));
            }

            # jika samakan nilai
            if (isset($_POST['samakan_nilai']) AND $_POST['samakan_nilai'] == 1) {
                foreach ($daftar_anggota as $key => $val) {
                    $check = tk_nilai_anggota('retrieve', array(
                        'kelompok_id' => $kelompok['id'],
                        'anggota_id'  => $val['id'],
                    ));
                    if (empty($check)) {
                        tk_nilai_anggota('insert', array(
                            'kelompok_id' => $kelompok['id'],
                            'anggota_id'  => $val['id'],
                            'nilai'       => $CI->input->post('nilai', true),
                            'catatan'     => '',
                        ));
                    } else {
                        tk_nilai_anggota('update', $check['id'], array(
                            'nilai' => $CI->input->post('nilai', true),
                        ));
                    }
                }
            }

            echo "1";
        }

        # nilai anggota
        if (!empty($_POST['nilai_anggota']) AND !empty($_POST['anggota_id'])) {
            $check = tk_nilai_anggota('retrieve', array(
                'kelompok_id' => $kelompok['id'],
                'anggota_id'  => $CI->input->post('anggota_id', true),
            ));
            if (empty($check)) {
                tk_nilai_anggota('insert', array(
                    'kelompok_id' => $kelompok['id'],
                    'anggota_id'  => $CI->input->post('anggota_id', true),
                    'nilai'       => $CI->input->post('nilai_anggota', true),
                    'catatan'     => $CI->input->post('catatan_anggota', true),
                ));
            } else {
                tk_nilai_anggota('update', $check['id'], array(
                    'nilai'   => $CI->input->post('nilai_anggota', true),
                    'catatan' => $CI->input->post('catatan_anggota', true),
                ));
            }

            echo "1";
        }

        die;
    }

    // pr($data);

    $CI->twig->display('tk-koreksi.html', $data);
}

function kerjakan($param1 = "")
{
    $CI =& get_instance();

    if (!is_siswa()) {  die; }

    $tugas_id = $param1;
    if (empty($tugas_id)) { show_error("Tugas ID dibutuhkan"); }

    $tugas = tk_tugas('retrieve', $tugas_id);
    if (empty($tugas)) { show_error("Tugas Tidak Ditemukan"); }

    # cek buka atau tidak
    if ($tugas['status'] == 1) { show_error("Tugas Belum Diterbitkan"); }

    $kelompok = tk_kelompok('retrieve_on_siswa', $tugas['id'], get_sess_data('user', 'id'));
    if (empty($kelompok)) {
        $CI->session->set_flashdata('tugas', get_alert('danger', 'Maaf Anda belum terdaftar pada kelompok manampun di tugas ini.'));
        redirect('plugins/tugas_kelompok');
    }
    $data['kelompok'] = $kelompok;

    $anggota = tk_anggota('retrieve', $kelompok['id'], get_sess_data('user', 'id'));
    if (empty($anggota)) {
        show_error("Anda tidak ditemukan pada daftar anggota.");
    }

    $ada_nilai = false;

    # cek sudah ngerjakan belum
    $nilai_kelompok = tk_nilai_kelompok('retrieve', $kelompok['id']);
    if (!empty($nilai_kelompok)) {
        $ada_nilai = true;
    }

    # cek nilai anggota ini
    $nilai_anggota = tk_nilai_anggota('retrieve', array(
        'kelompok_id' => $kelompok['id'],
        'anggota_id'  => $anggota['id'],
    ));
    if (!empty($nilai_anggota)) {
        $ada_nilai = true;
    }

    $data['ada_nilai'] = $ada_nilai;

    # ambil kerjaan anggota ini
    $kerjaan = tk_kerjaan('retrieve', $anggota['id']);
    $data['kerjaan'] = $kerjaan;

    # cari daftar anggota
    $daftar_anggota = tk_anggota('retrieve_all', $kelompok['id']);

    # cari penilaian anggota ini
    foreach ($daftar_anggota as $key => $val) {
        $val['penilaian_anggota'] = tk_penilaian_anggota('retrieve', array(
            'kelompok_id'        => $kelompok['id'],
            'penilai_anggota_id' => $anggota['id'],
            'anggota_id'         => $val['id'],
        ));

        $daftar_anggota[$key] = $val;
    }

    $data['daftar_anggota'] = $daftar_anggota;

    # aksi post dan masih terbit
    if ($tugas['status'] == 2) {

        # simpan jawaban
        if (!is_ajax() AND isset($_POST['jawaban'])) {
            $CI->form_validation->set_rules('jawaban', 'Jawaban', 'trim');
            if ($CI->form_validation->run() == true) {
                if (empty($kerjaan)) {
                    # tambahkan dulu
                    $kerjaan_id = tk_kerjaan('insert', array(
                        'anggota_id' => $anggota['id'],
                        'konten'     => $CI->input->post('jawaban'),
                        'tgl_input'  => date('Y-m-d H:i:s'),
                    ));
                } else {
                    tk_kerjaan('update', $kerjaan['id'], array(
                        'konten'    => $CI->input->post('jawaban'),
                        'tgl_input' => date('Y-m-d H:i:s'),
                    ));
                }

                $CI->session->set_flashdata('tugas', get_alert('success', 'Jawaban Berhasil Disimpan.'));
                redirect('plugins/tugas_kelompok/kerjakan/' . $tugas['id']);
            }
        }

        # simpan nilai
        if (is_ajax() AND !empty($_POST['nilai'])) {
            $check = tk_penilaian_anggota('retrieve', array(
                'kelompok_id'        => $kelompok['id'],
                'penilai_anggota_id' => $anggota['id'],
                'anggota_id'         => $CI->input->post('anggota_id', true),
            ));
            if (empty($check)) {
                tk_penilaian_anggota('insert', array(
                    'kelompok_id'        => $kelompok['id'],
                    'penilai_anggota_id' => $anggota['id'],
                    'anggota_id'         => $CI->input->post('anggota_id', true),
                    'nilai'              => $CI->input->post('nilai', true),
                    'alasan'             => $CI->input->post('alasan', true),
                ));
            } else {
                tk_penilaian_anggota('update', $check['id'], array(
                    'nilai'  => $CI->input->post('nilai', true),
                    'alasan' => $CI->input->post('alasan', true),
                ));
            }

            echo "1";

            die;
        }
    }

    $tugas = tk_tugas('format', $tugas);
    $data['tugas'] = $tugas;

    # cari list kerjaan
    $list_kerjaan = tk_kerjaan('retrieve_all_by_kelompok', $kelompok['id']);
    $data['list_kerjaan'] = $list_kerjaan;

    $data['comp_js'] = get_texteditor();

    $CI->twig->display('tk-kerjakan.html', $data);
}

function nilai($param1 = "")
{
    $CI =& get_instance();

    if (!is_siswa()) {  die; }

    $tugas_id = $param1;
    if (empty($tugas_id)) { show_error("Tugas ID Dibutuhkan"); }

    $tugas = tk_tugas('retrieve', $tugas_id);
    if (empty($tugas)) { show_error("Tugas Tidak Ditemukan"); }

    # cek buka atau tidak
    if ($tugas['status'] == 1) { show_error("Tugas Belum Diterbitkan"); }

    $kelompok = tk_kelompok('retrieve_on_siswa', $tugas['id'], get_sess_data('user', 'id'));
    if (empty($kelompok)) {
        $CI->session->set_flashdata('tugas', get_alert('danger', 'Maaf Anda belum terdaftar pada kelompok manampun di tugas ini.'));
        redirect('plugins/tugas_kelompok');
    }
    $data['kelompok'] = $kelompok;

    $anggota = tk_anggota('retrieve', $kelompok['id'], get_sess_data('user', 'id'));
    if (empty($anggota)) {
        show_error("Anda tidak ditemukan pada daftar anggota.");
    }

    # cek sudah ngerjakan belum
    $nilai_kelompok = tk_nilai_kelompok('retrieve', $kelompok['id']);
    $data['nilai_kelompok'] = $nilai_kelompok;

    # cari daftar anggota
    $daftar_anggota = tk_anggota('retrieve_all', $kelompok['id']);

    # cari penilaian anggota ini
    foreach ($daftar_anggota as $key => $val) {
        $val['nilai_anggota'] = tk_nilai_anggota('retrieve', array(
            'kelompok_id'        => $kelompok['id'],
            'anggota_id'         => $val['id'],
        ));

        $daftar_anggota[$key] = $val;
    }
    $data['daftar_anggota'] = $daftar_anggota;

    $tugas = tk_tugas('format', $tugas);
    $data['tugas'] = $tugas;

    # cari list kerjaan
    $list_kerjaan = tk_kerjaan('retrieve_all_by_kelompok', $kelompok['id']);
    $data['list_kerjaan'] = $list_kerjaan;

    $CI->twig->display('tk-nilai.html', $data);
}

function ajax($param1 = "")
{
    $CI =& get_instance();

    if (!is_ajax()) {
        die;
    }

    switch ($param1) {
        case 'add-to-sess':
            if (empty($_POST['siswa_id'])) { die; }
            $siswa_id = $_POST['siswa_id'];

            if (empty($_POST['tugas_id'])) { die; }
            $tugas_id = $_POST['tugas_id'];

            # cek sudah ada dikelompok lain belum
            $check_siswa = tk_anggota('check_siswa', $tugas_id, $siswa_id);
            if (!empty($check_siswa)) {
                echo "0";die;
            }

            $session_add = $CI->session->userdata('tk_session_add');
            if (empty($session_add[$tugas_id][$siswa_id])) {
                $session_add[$tugas_id][$siswa_id] = $CI->siswa_model->retrieve($siswa_id);

                $CI->session->set_userdata('tk_session_add', $session_add);
            }

            echo "1";
        break;

        case 'add-to-sess-edit':
            if (empty($_POST['siswa_id'])) { die; }
            $siswa_id = $_POST['siswa_id'];

            if (empty($_POST['kelompok_id'])) { die; }
            $kelompok_id = $_POST['kelompok_id'];

            if (empty($_POST['tugas_id'])) { die; }
            $tugas_id = $_POST['tugas_id'];

            # cek sudah ada dikelompok lain belum
            $check_siswa = tk_anggota('check_siswa_edit', $tugas_id, $siswa_id, $kelompok_id);
            if (!empty($check_siswa)) {
                echo "0";die;
            }

            $session_edit = $CI->session->userdata('tk_session_edit');
            if (empty($session_edit[$kelompok_id][$siswa_id])) {
                $session_edit[$kelompok_id][$siswa_id] = $CI->siswa_model->retrieve($siswa_id);

                $CI->session->set_userdata('tk_session_edit', $session_edit);
            }

            echo "1";
        break;

        case 'get-add-sess':
            if (empty($_GET['tugas_id'])) { die; }
            $tugas_id = $_GET['tugas_id'];

            $session_add = $CI->session->userdata('tk_session_add');

            $results = array();
            if (!empty($session_add[$tugas_id])) {
                $results = $session_add[$tugas_id];
            }

            echo json_encode($results);
        break;

        case 'get-edit-sess':
            if (empty($_GET['kelompok_id'])) { die; }
            $kelompok_id = $_GET['kelompok_id'];

            $session_edit = $CI->session->userdata('tk_session_edit');

            # jika pertama kali load yang ada
            if ($_GET['pertama'] == 1) {
                $add_session_edit = array();
                $daftar_anggota = tk_anggota('retrieve_all', $kelompok_id);
                foreach ($daftar_anggota as $key => $val) {
                    $add_session_edit[$val['siswa']['id']] = $val['siswa'];
                }

                $CI->session->set_userdata('tk_session_edit', array(
                    $kelompok_id => $add_session_edit,
                ));

                $session_edit = $CI->session->userdata('tk_session_edit');
            }

            $results = array();
            if (!empty($session_edit[$kelompok_id])) {
                $results = $session_edit[$kelompok_id];
            }

            echo json_encode($results);
        break;

        case 'delete-add-sess':
            if (empty($_POST['siswa_id'])) {  die; }
            $siswa_id = $_POST['siswa_id'];

            if (empty($_POST['tugas_id'])) { die; }
            $tugas_id = $_POST['tugas_id'];

            $session_add = $CI->session->userdata('tk_session_add');
            if (!empty($session_add[$tugas_id][$siswa_id])) {
                unset($session_add[$tugas_id][$siswa_id]);
            }

            $CI->session->set_userdata('tk_session_add', $session_add);
        break;

        case 'delete-edit-sess':
            if (empty($_POST['siswa_id'])) {  die; }
            $siswa_id = $_POST['siswa_id'];

            if (empty($_POST['kelompok_id'])) { die; }
            $kelompok_id = $_POST['kelompok_id'];

            $session_edit = $CI->session->userdata('tk_session_edit');
            if (!empty($session_edit[$kelompok_id][$siswa_id])) {
                unset($session_edit[$kelompok_id][$siswa_id]);
            }

            $CI->session->set_userdata('tk_session_edit', $session_edit);
        break;

        case 'cari-siswa':
            $kelas_id = $_GET['kelas_id'];
            $query    = $_GET['query'];

            $results = $CI->siswa_model->retrieve_all_filter(
                $nis           = '',
                $nama          = $query,
                $jenis_kelamin = array(),
                $tahun_masuk   = '',
                $tempat_lahir  = '',
                $tgl_lahir     = '',
                $bln_lahir     = '',
                $thn_lahir     = '',
                $alamat        = '',
                $agama         = array(),
                $kelas_id      = array($kelas_id),
                $status_id     = array(),
                $username      = '',
                $page_no       = 1,
                $pagination    = false
            );

            $data['suggestions'] = array();
            foreach ($results as $r) {
                $data['suggestions'][] = array('value' => "{$r['nis']} - {$r['nama']}", 'id' => $r['id']);
            }

            echo json_encode($data);
        break;
    }
}
