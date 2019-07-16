<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function construct()
{
    # cek session
    must_login();

    # jika bukan admin/pengajar
    if (is_siswa()) {
        show_error("Akses Ditolak");
    }

    # include model
    include 'func_model.php';

    # create table
    bs_create_table();
}

function index($param1 = "", $param2 = "", $tugas_id_iframe_copy = 0)
{
    $CI =& get_instance();

    $param1      = (int)$param1;
    $param1      = empty($param1) ? 1 : $param1;
    $pengajar_id = array();
    $mapel_id    = $param2;
    $keyword     = "";
    $bs_config   = bs_config();

    # default tugas pada iframe copy soal bank
    if (!empty($tugas_id_iframe_copy) AND empty($mapel_id)) {
        $tugas = $CI->tugas_model->retrieve($tugas_id_iframe_copy);
        $mapel_id = $tugas['mapel_id'];
    }

    $mapel_id = (int)$mapel_id;

    if (!empty($_GET['q'])) {
        $keyword = (string)urldecode($_GET['q']);
        # cari id pengajar dengan nama sesuai keyword
        $filter_pengajar = $CI->pengajar_model->retrieve_all_filter(
            $nip           = '',
            $nama          = $keyword,
            $jenis_kelamin = array(),
            $tempat_lahir  = '',
            $tgl_lahir     = '',
            $bln_lahir     = '',
            $thn_lahir     = '',
            $alamat        = '',
            $status_id     = array(),
            $username      = '',
            $is_admin      = '',
            $page_no       = 1,
            $pagination    = false
        );
        foreach ($filter_pengajar as $p) {
            $pengajar_id[] = $p['id'];
        }
    }

    /** start validasi */
    if (is_pengajar() AND !empty($bs_config['tampil_dipengajar']) AND $bs_config['tampil_dipengajar'] == 2) {
        $pengajar_id = array(get_sess_data('user', 'id'));
    }

    if (is_admin() AND !empty($bs_config['tampil_diadmin']) AND $bs_config['tampil_diadmin'] == 2) {
        $pengajar_id = array(get_sess_data('user', 'id'));
    }
    /** end validasi */

    $retrieve_all = bs_retrieve_all(
        $no_of_records = 20,
        $page_no       = $param1,
        $pengajar_id,
        $mapel_id,
        $keyword
    );

    foreach ($retrieve_all['results'] as $key => $val) {
        $user = $CI->pengajar_model->retrieve($val['pengajar_id']);
        if (!empty($user)) {
            if (is_admin()) {
                $user['link_profil'] = site_url('pengajar/detail/' . $user['status_id'] . '/' . $user['id']);
            } else {
                $user['link_profil'] = site_url('pengajar/detail/' . $user['id']);
            }
            $user['link_image'] = get_url_image_pengajar($user['foto'], 'medium', $user['jenis_kelamin']);
            $val['user']        = $user;
        }

        # cari mapel
        $mapel = $CI->mapel_model->retrieve($val['mapel_id']);
        if (!empty($mapel)) {
            $val['mapel'] = $mapel;
        }
        $retrieve_all['results'][$key] = $val;
    }

    $data['mapel_id']   = $mapel_id;
    $data['keyword']    = $keyword;
    $data['page_no']    = $param1;
    $data['pertanyaan'] = $retrieve_all['results'];

    $add_pagination_url = array($mapel_id);
    if (!empty($keyword)) {
        $add_pagination_url[] = "?q={$keyword}";
    }
    $data['pagination'] = $CI->pager->view($retrieve_all, 'plugins/bank_soal/' . (!empty($tugas_id_iframe_copy) ? "copy_soal_bank/{$tugas_id_iframe_copy}" : 'index') . '/', $add_pagination_url);

    # ambil semua data mapel
    $CI->db->where('aktif', 1);
    $results = $CI->db->get('mapel');
    $data['mapel'] = $results->result_array();

    # panggil component
    $html_js = get_texteditor();
    $html_js .= load_comp_js(array(
        base_url('assets/comp/colorbox/jquery.colorbox-min.js'),
        base_url_plugins('src/bank_soal/js/colorbox_bank_soal.js'),
        base_url_plugins('src/bank_soal/js/bank_soal.js'),
    ));

    $data['comp_js']  = $html_js;
    $data['comp_css'] = load_comp_css(array(base_url('assets/comp/colorbox/colorbox.css')));

    if (empty($tugas_id_iframe_copy)) {
        $CI->twig->display('bank-soal.html', $data);
    } else {
        $data['tugas_id_iframe_copy'] = $tugas_id_iframe_copy;

        $session_key = 'bank-soal-tandai';
        $session_tandai = $CI->session->userdata($session_key);
        $data['session_tandai'] = isset($session_tandai[$tugas_id_iframe_copy]) ? $session_tandai[$tugas_id_iframe_copy] : array();

        $CI->twig->display('copy-bank-soal.html', $data);
    }
}

function pengaturan()
{
    if (!is_admin()) {
        show_error('Akses ditolak');
    }

    $CI =& get_instance();
    $bs_config = bs_config();

    if (!is_demo_app()) {
        $CI->form_validation->set_rules('tampil_diadmin', 'Tampil soal diadmin', 'required|integer');
        $CI->form_validation->set_rules('tampil_dipengajar', 'Tampil soal dipengajar', 'required|integer');
        if ($CI->form_validation->run() == TRUE) {
            $ta = $CI->input->post('tampil_diadmin', true);
            $tp = $CI->input->post('tampil_dipengajar', true);
            bs_config('update', array(
                'tampil_diadmin' => $ta,
                'tampil_dipengajar' => $tp,
            ));

            $CI->session->set_flashdata('pengaturan', get_alert('success', 'Pengaturan Berhasil Diperbaharui.'));
            redirect('plugins/bank_soal/pengaturan');
        }
    }

    $CI->twig->display('pengaturan-bank-soal.html', $bs_config);
}

function search($tugas_id_iframe_copy = 0)
{
    if (empty($_POST['mapel_id']) AND empty($_POST['q'])) {
        redirect('plugins/bank_soal/' . (!empty($tugas_id_iframe_copy) ? "copy_soal_bank/{$tugas_id_iframe_copy}" : 'index'));
    } else {
        $mapel_id = $_POST['mapel_id'];
        $q = urlencode($_POST['q']);
        redirect('plugins/bank_soal/'. (!empty($tugas_id_iframe_copy) ? "copy_soal_bank/{$tugas_id_iframe_copy}" : 'index') .'/1/' . $mapel_id . (!empty($q) ? "/?q={$q}" : ""));
    }
}

function copy_soal_bank($param1 = "", $param2 = "", $param3 = "")
{
    $CI =& get_instance();

    # harus admin atau pengajar
    if (!is_admin() AND !is_pengajar()) {
        show_error('Akses ditolak.');
    }

    $tugas_id = (int)$param1;
    $tugas    = $CI->tugas_model->retrieve($tugas_id);
    if (empty($tugas) OR $tugas['type_id'] == 1) {
        show_error('Tugas tidak valid.');
    }

    # jika sebagai pengajar, cek kepemilikan
    if (is_pengajar() AND $tugas['pengajar_id'] != get_sess_data('user', 'id')) {
        show_error("Anda bukan pembuat tugas.");
    }

    index($param2, $param3, $param1);
}

function do_copy_soal_bank($tugas_id)
{
    $CI =& get_instance();
    $session_key = 'bank-soal-tandai';

    # harus admin atau pengajar
    if (!is_admin() AND !is_pengajar()) {
        show_error('Akses ditolak.');
    }

    $tugas_id = (int)$tugas_id;
    $tugas    = $CI->tugas_model->retrieve($tugas_id);
    if (empty($tugas) OR $tugas['type_id'] == 1) {
        show_error('Tugas tidak valid.');
    }

    # jika sebagai pengajar, cek kepemilikan
    if (is_pengajar() AND $tugas['pengajar_id'] != get_sess_data('user', 'id')) {
        show_error("Anda bukan pembuat tugas.");
    }

    $session_tandai = $CI->session->userdata($session_key);
    if (!empty($session_tandai[$tugas_id])) {
        $banks = bs_retrieve($session_tandai[$tugas_id]);

        foreach ($banks as $bank) {
            # simpan pertanyaan
            $pertanyaan_id = $CI->tugas_model->create_pertanyaan(
                $bank['pertanyaan'],
                $tugas['id']
            );

            # jika pilihan ganda
            if ($tugas['type_id'] == 3) {
                if (empty($bank['kunci'])) {
                    $bank['kunci'] = 'a';
                }

                $arr_label = array(1 => 'a', 'b', 'c', 'd', 'e');
                for ($i = 1; $i <= 5; $i++) {
                    $pilihan = empty($bank['pilihan_' . $arr_label[$i]]) ? "" : $bank['pilihan_' . $arr_label[$i]];

                    $CI->tugas_model->create_pilihan(
                        $pertanyaan_id,
                        $pilihan,
                        (array_search($bank['kunci'], $arr_label) == $i) ? 1 : 0,
                        $i
                    );
                }
            }
        }

        # hapus session
        unset($session_tandai[$tugas_id]);
        $CI->session->set_userdata($session_key, $session_tandai);

        $CI->session->set_flashdata('tugas', get_alert('success', 'Soal Berhasil Dicopy.'));
    }
    ?>
    <script type="text/javascript">
    parent.jQuery.colorbox.close();
    </script>
    <?php
}

function ajax($get = "")
{
    $CI =& get_instance();

    if (!is_ajax()) {
        die("Akses ditolak");
    }

    $session_key = 'bank-soal-tandai';

    switch ($get) {
        case 'mapel_dropdown':
            $CI->db->where('aktif', 1);
            $results = $CI->db->get('mapel');
            echo json_encode($results->result_array());
        break;

        case 'tandai':
            $tugas_id = $CI->input->post('tugas_id', true);
            $pid = $CI->input->post('pid', true);
            $act = $CI->input->post('act', true);

            $session_tandai = $CI->session->userdata($session_key);

            if ($act == 'add') {
                if (empty($session_tandai[$tugas_id])) {
                    $CI->session->set_userdata($session_key, array(
                        $tugas_id => array($pid),
                    ));
                } else {
                    if (!in_array($pid, $session_tandai[$tugas_id])) {
                        array_push($session_tandai[$tugas_id], $pid);
                        $CI->session->set_userdata($session_key, $session_tandai);
                    }
                }
            } elseif ($act == 'remove') {
                if (!empty($session_tandai[$tugas_id]) AND in_array($pid, $session_tandai[$tugas_id])) {
                    $find_key = array_search($pid, $session_tandai[$tugas_id]);
                    unset($session_tandai[$tugas_id][$find_key]);
                    $CI->session->set_userdata($session_key, $session_tandai);
                }
            }

            echo "1";
        break;

        case 'get_tandai':
            $tugas_id = $_GET['tugas_id'];
            $session_tandai = $CI->session->userdata($session_key);

            if (!empty($session_tandai[$tugas_id])) {
                echo '<table class="table table-striped">
                    <tbody>
                        <tr class="info">
                            <td><b>ID Soal yang ditandai:</b> ' . implode(', ', $session_tandai[$tugas_id]) . '</td>
                            <td width="15%">
                                <a href="' . site_url('plugins/bank_soal/do_copy_soal_bank/' . $tugas_id) . '" class="btn btn-success"><i class="icon icon-share-alt"></i> Copy Soal</a>
                            </td>
                        </tr>
                    </tbody>
                </table>';
            } else {
                echo "0";
            }
        break;
    }
}

function simpan()
{
    $CI =& get_instance();

    if (!empty($_POST['pertanyaan'])) {
        $pertanyaan = $CI->input->post('pertanyaan');
        $mapel_id   = $CI->input->post('mapel_id', true);
        $mapel_id   = empty($mapel_id) ? null : $mapel_id;
        $pilihan_a  = null;
        $pilihan_b  = null;
        $pilihan_c  = null;
        $pilihan_d  = null;
        $pilihan_e  = null;
        $kunci      = null;
        if (!empty($_POST['pilihan'])) {
            foreach ($_POST['pilihan'] as $key => $val) {
                ${"pilihan_$key"} = $val;
            }
        }
        if (!empty($_POST['kunci'])) {
            $kunci = $CI->input->post('kunci', true);
            $kunci = strtolower($kunci);
            if (!in_array($kunci, array('a', 'b', 'c', 'd', 'e'))) {
                $kunci = 'a';
            }
        }

        bs_create(
            get_sess_data('user', 'id'),
            $mapel_id,
            $pertanyaan,
            $pilihan_a,
            $pilihan_b,
            $pilihan_c,
            $pilihan_d,
            $pilihan_e,
            $kunci
        );

        $CI->session->set_flashdata('bank', get_alert('success', 'Soal Berhasil Disimpan.'));
    }

    redirect('plugins/bank_soal');
}

function edit($id = "", $uri_back = "")
{
    $CI =& get_instance();

    $id = (int)$id;
    $p  = bs_retrieve($id);

    if (empty($p)) {
        show_error('Pertanyaan Tidak Ditemukan.');
    }

    if (!empty($uri_back)) {
        $uri_back = deurl_redirect($uri_back);
    } else {
        $uri_back = site_url('plugins/bank_soal');
    }
    $data['uri_back'] = $uri_back;

    # jika sebagai pengajar, cek pembuatnya
    if (is_pengajar() AND get_sess_data('user', 'id') != $p['pengajar_id']) {
        $CI->session->set_flashdata('bank', get_alert('warning', 'Soal hanya dapat diperbaharui oleh Administrator atau Pembuat.'));
        redirect($uri_back);
    }

    # aksi simpan edit
    $CI->form_validation->set_rules('mapel_id', 'Matapelajaran', 'required');
    $CI->form_validation->set_rules('pertanyaan', 'Pertanyaan', 'required');
    if ($CI->form_validation->run() == TRUE) {
        $pilihan_a = $CI->input->post('pilihan_a');
        $pilihan_b = $CI->input->post('pilihan_b');
        $pilihan_c = $CI->input->post('pilihan_c');
        $pilihan_d = $CI->input->post('pilihan_d');
        $pilihan_e = $CI->input->post('pilihan_e');
        $kunci     = $CI->input->post('kunci', true);

        bs_update(
            $p['id'],
            $p['pengajar_id'],
            $CI->input->post('mapel_id', TRUE),
            $CI->input->post('pertanyaan'),
            empty($pilihan_a) ? null : $pilihan_a,
            empty($pilihan_b) ? null : $pilihan_b,
            empty($pilihan_c) ? null : $pilihan_c,
            empty($pilihan_d) ? null : $pilihan_d,
            empty($pilihan_e) ? null : $pilihan_e,
            empty($kunci) ? null : $kunci
        );

        $CI->session->set_flashdata('bank', get_alert('success', 'Soal Berhasil Diperbaharui.'));
        redirect('plugins/bank_soal/edit/' . $p['id'] . '/' . enurl_redirect($uri_back));
    }

    # ambil semua data mapel
    $CI->db->where('aktif', 1);
    $results = $CI->db->get('mapel');
    $data['mapel'] = $results->result_array();

    # panggil component
    $html_js = get_texteditor();
    $data['comp_js'] = $html_js;

    $data['p'] = $p;
    $CI->twig->display('edit-bank.html', $data);
}

function delete($id = "", $uri_back = "")
{
    $CI =& get_instance();

    $id = (int)$id;
    $p  = bs_retrieve($id);

    if (empty($p)) {
        show_error('Pertanyaan tidak ditemukan.');
    }

    if (!empty($uri_back)) {
        $uri_back = deurl_redirect($uri_back);
    } else {
        $uri_back = site_url('plugins/bank_soal');
    }

    # jika sebagai pengajar, cek pembuatnya
    if (is_pengajar() AND get_sess_data('user', 'id') != $p['pengajar_id']) {
        $CI->session->set_flashdata('bank', get_alert('warning', 'Soal anya dapat dihapus oleh Administrator atau Pembuat.'));
        redirect($uri_back);
    }

    bs_delete($p['id']);

    $CI->session->set_flashdata('bank', get_alert('success', 'Soal Berhasil Dihapus.'));
    redirect($uri_back);
}

function copy_soal_tugas()
{
    $CI =& get_instance();
    $bs_config = bs_config();

    # aksi untuk copy pertanyaan
    if (!empty($_GET['copy'])) {
        $pertanyaan_id = (int)$_GET['copy'];
        $pertanyaan    = $CI->tugas_model->retrieve_pertanyaan($pertanyaan_id);
        if (empty($pertanyaan)) {
            show_error("Pertanyaan tidak ditemukan");
        }

        $pilihan_a = null;
        $pilihan_b = null;
        $pilihan_c = null;
        $pilihan_d = null;
        $pilihan_e = null;
        $kunci     = null;

        $label_order = array(1 => "a", "b", "c", "d", "e");

        # cari pilihan
        $pilihan = $CI->tugas_model->retrieve_all_pilihan($pertanyaan['id']);
        foreach ($pilihan as $p) {
            $label = $label_order[$p['urutan']];
            ${"pilihan_$label"} = $p['konten'];

            if ($p['kunci'] == 1) {
                $kunci = $label;
            }
        }

        $retrieve_tugas = $CI->tugas_model->retrieve($pertanyaan['tugas_id']);

        # simpan
        bs_create(
            get_sess_data('user', 'id'),
            $retrieve_tugas['mapel_id'],
            $pertanyaan['pertanyaan'],
            $pilihan_a,
            $pilihan_b,
            $pilihan_c,
            $pilihan_d,
            $pilihan_e,
            $kunci
        );

        $CI->session->set_flashdata('copy', get_alert('success', "Pertanyaan ID $pertanyaan_id berhasil dicopy."));
        redirect('plugins/bank_soal/copy_soal_tugas');
    }

    # variabel untuk nyimpen biar tidak boros query
    $arr_tugas_id    = array();
    $arr_pengajar_id = array();

    # ambil semua pertanyaan
    $retrieve_all_pertanyaan = $CI->tugas_model->retrieve_all_pertanyaan('all', 1, null);
    foreach ($retrieve_all_pertanyaan as $key => $val) {
        # dapatkan informasi pembuat pertanyaan dan pada tugas apa
        if (!isset($arr_tugas_id[$val['tugas_id']])) {
            $info_tugas = $CI->tugas_model->retrieve($val['tugas_id']);
            $arr_tugas_id[$val['tugas_id']] = $CI->tugas_model->retrieve($val['tugas_id']);
        } else {
            $info_tugas = $arr_tugas_id[$val['tugas_id']];
        }

        /** start validasi */
        if (is_pengajar() AND !empty($bs_config['tampil_dipengajar']) AND $bs_config['tampil_dipengajar'] == 2) {
            if (get_sess_data('user', 'id') != $info_tugas['pengajar_id']) {
                unset($retrieve_all_pertanyaan[$key]);
                continue;
            }
        }

        if (is_admin() AND !empty($bs_config['tampil_diadmin']) AND $bs_config['tampil_diadmin'] == 2) {
            if (get_sess_data('user', 'id') != $info_tugas['pengajar_id']) {
                unset($retrieve_all_pertanyaan[$key]);
                continue;
            }
        }
        /** end validasi */

        if (!isset($arr_pengajar_id[$info_tugas['pengajar_id']])) {
            $info_pembuat = $CI->pengajar_model->retrieve($info_tugas['pengajar_id']);
        } else {
            $info_pembuat = $arr_pengajar_id[$info_tugas['pengajar_id']];
        }

        if (is_admin()) {
            $info_pembuat['link_profil'] = site_url('pengajar/detail/'.$info_pembuat['status_id'].'/'.$info_pembuat['id']);
        } else {
            $info_pembuat['link_profil'] = site_url('pengajar/detail/'.$info_pembuat['id']);
        }

        $val['info_tugas']   = $info_tugas;
        $val['info_pembuat'] = $info_pembuat;

        # cari pilihan
        $pilihan = $CI->tugas_model->retrieve_all_pilihan($val['id']);
        if (!empty($pilihan)) {
            $val['pilihan'] = $pilihan;
        }

        $retrieve_all_pertanyaan[$key] = $val;
    }

    $data['pertanyaan'] = $retrieve_all_pertanyaan;

    # panggil datatables
    $data['comp_js'] = load_comp_js(array(
        base_url('assets/comp/datatables/jquery.dataTables.js'),
        base_url('assets/comp/datatables/datatable-bootstrap2.js'),
    ));

    $data['comp_css'] = load_comp_css(array(
        base_url('assets/comp/datatables/datatable-bootstrap2.css'),
    ));

    $CI->twig->display('copy-soal-tugas.html', $data);
}

function import_excel()
{
    $CI =& get_instance();

    $data['upload_error'] = '';

    $CI->form_validation->set_rules('mapel_id', 'Mata Pelajaran', 'required');
    if ($CI->form_validation->run() == true) {
        if (!empty($_FILES['userfile']['tmp_name'])) {
            $filename = $_FILES['userfile']['name'];
            $ext      = pathinfo($filename, PATHINFO_EXTENSION);
            if (!in_array($ext, array('xls')) ) {
                $data['upload_error'] = '<span class="text-error"><i class="icon-info-sign"></i> File Harus Bertipe xls.</span>';
            } else {
                $data_excel   = data_excel($_FILES['userfile']['tmp_name']);
                $daftar_kunci = array("a", "b", "c", "d", "e");

                $berhasil = 0;
                foreach ($data_excel as $row) {
                    # jika essay
                    if (empty($row[1])) {
                        $pilihan_a = null;
                        $pilihan_b = null;
                        $pilihan_c = null;
                        $pilihan_d = null;
                        $pilihan_e = null;
                        $kunci     = null;
                    } else {
                        $pilihan_a = !empty($row[1]) ? $row[1] : "";
                        $pilihan_b = !empty($row[2]) ? $row[2] : "";
                        $pilihan_c = !empty($row[3]) ? $row[3] : "";
                        $pilihan_d = !empty($row[4]) ? $row[4] : "";
                        $pilihan_e = !empty($row[5]) ? $row[5] : "";
                        $kunci     = !empty($row[6]) ? $row[6] : "A";
                        $kunci     = strtolower($kunci);
                        if (!in_array($kunci, $daftar_kunci)) {
                            $kunci = 'a';
                        }
                    }

                    bs_create(
                        get_sess_data('user', 'id'),
                        $CI->input->post('mapel_id', true),
                        $row[0],
                        $pilihan_a,
                        $pilihan_b,
                        $pilihan_c,
                        $pilihan_d,
                        $pilihan_e,
                        $kunci
                    );

                    $berhasil++;
                }

                $CI->session->set_flashdata('bank', get_alert('success', "$berhasil soal baru berhasil diimport."));
                redirect('plugins/bank_soal/import_excel');
            }
        } else {
            $data['upload_error'] = '<span class="text-error"><i class="icon-info-sign"></i> File Dibutuhkan.</span>';
        }
    }

    # ambil semua data mapel
    $CI->db->where('aktif', 1);
    $results = $CI->db->get('mapel');
    $data['mapel'] = $results->result_array();

    $CI->twig->display('import-excel-bank.html', $data);
}

function add_soal($tugas_id = "")
{
    $CI =& get_instance();

    # harus admin atau pengajar
    if (!is_admin() AND !is_pengajar()) {
        $CI->session->set_flashdata('tugas', get_alert('warning', 'Akses ditolak.'));
        redirect('tugas');
    }

    $tugas_id = (int)$tugas_id;
    $tugas    = $CI->tugas_model->retrieve($tugas_id);
    if (empty($tugas) OR $tugas['type_id'] == 1) {
        $CI->session->set_flashdata('tugas', get_alert('warning', 'Tugas tidak valid.'));
        redirect('tugas');
    }

    # jika sebagai pengajar, cek kepemilikan
    if (is_pengajar() AND $tugas['pengajar_id'] != get_sess_data('user', 'id')) {
        $CI->session->set_flashdata('tugas', get_alert('warning', 'Anda bukan pembuat tugas.'));
        redirect('tugas');
    }

    # post soal
    if (!empty($_POST)) {
        $pertanyaan = $CI->input->post('pertanyaan');
        if (!empty($pertanyaan)) {
            # simpan pertanyaan
            $pertanyaan_id = $CI->tugas_model->create_pertanyaan($pertanyaan, $tugas['id']);
            $post_pilihan  = $CI->input->post('pilihan');
            $post_kunci    = $CI->input->post('kunci', true);
            if (!empty($post_pilihan)) {
                foreach ($post_pilihan as $key => $val) {
                    $kunci = 0;
                    if ($post_kunci == $key) {
                        $kunci = 1;
                    }
                    $CI->tugas_model->create_pilihan($pertanyaan_id, $val, $kunci);
                }
            }

            $CI->session->set_flashdata('tugas', get_alert('success', 'Soal Berhasil Ditambah.'));
        }
    }

    redirect('tugas/manajemen_soal/' . $tugas['id']);
}

function edit_soal($tugas_id, $pertanyaan_id, $uri_back = "")
{
    $CI =& get_instance();

    # harus admin atau pengajar
    if (!is_admin() AND !is_pengajar()) {
        $CI->session->set_flashdata('tugas', get_alert('warning', 'Akses Ditolak.'));
        redirect('tugas');
    }

    $tugas_id = (int)$tugas_id;
    $tugas    = $CI->tugas_model->retrieve($tugas_id);
    if (empty($tugas) OR $tugas['type_id'] == 1) {
        $CI->session->set_flashdata('tugas', get_alert('warning', 'Tugas Tidak Valid.'));
        redirect('tugas');
    }
    $data['tugas'] = $tugas;

    # jika sebagai pengajar, cek kepemilikan
    if (is_pengajar() AND $tugas['pengajar_id'] != get_sess_data('user', 'id')) {
        $CI->session->set_flashdata('tugas', get_alert('warning', 'Anda bukan pembuat tugas.'));
        redirect('tugas');
    }

    # cari pertanyaan
    $pertanyaan = $CI->tugas_model->retrieve_pertanyaan($pertanyaan_id);
    if (empty($pertanyaan)) {
        show_error("Pertanyaan tidak ditemukan.");
    }
    $data['pertanyaan'] = $pertanyaan;

    # aksi simpan
    $CI->form_validation->set_rules('pertanyaan', 'Pertanyaan', 'required|trim');
    if ($tugas['type_id'] == 3) {
        $CI->form_validation->set_rules('pilihan_a', 'Pilihan A', 'required|trim');
        $CI->form_validation->set_rules('pilihan_b', 'Pilihan B', 'trim');
        $CI->form_validation->set_rules('pilihan_c', 'Pilihan C', 'trim');
        $CI->form_validation->set_rules('pilihan_d', 'Pilihan D', 'trim');
        $CI->form_validation->set_rules('pilihan_e', 'Pilihan E', 'trim');
        $CI->form_validation->set_rules('kunci', 'Kunci', 'required|trim');
    }
    if ($CI->form_validation->run() == true) {
        $post_pertanyaan = $CI->input->post('pertanyaan');

        # update pertanyaan
        $CI->tugas_model->update_pertanyaan($pertanyaan['id'], $post_pertanyaan, $pertanyaan['urutan'], $tugas['id']);

        if ($tugas['type_id'] == 3) {
            $post_kunci = $CI->input->post('kunci', true);
            $arr_label  = array(1 => 'a', 'b', 'c', 'd', 'e');
            for ($i = 1; $i <= 5; $i++) {
                $CI->db->where('pertanyaan_id', $pertanyaan['id']);
                $CI->db->where('urutan', $i);
                $CI->db->where('aktif', 1);
                $result = $CI->db->get('pilihan');
                $result = $result->row_array();

                if (!empty($result)) {
                    $CI->db->where('id', $result['id']);
                    $CI->db->update('pilihan', array(
                        'konten' => $CI->input->post('pilihan_' . $arr_label[$i]),
                        'kunci'  => ($post_kunci == $arr_label[$i]) ? 1 : 0
                    ));
                } else {
                    $CI->db->insert('pilihan', array(
                        'pertanyaan_id' => $pertanyaan['id'],
                        'konten'        => $CI->input->post('pilihan_' . $arr_label[$i]),
                        'kunci'         => ($post_kunci == $arr_label[$i]) ? 1 : 0,
                        'urutan'        => $i,
                        'aktif'         => 1
                    ));
                }
            }
        }

        $CI->session->set_flashdata('bank', get_alert('success', 'Soal Berhasil Diperbaharui.'));
        redirect('plugins/bank_soal/edit_soal/' . $tugas['id'] . '/' . $pertanyaan['id'] . '/' . $uri_back);
    }

    # cari pilihan
    $data['pilihan'] = $CI->tugas_model->retrieve_all_pilihan($pertanyaan['id']);

    if (!empty($uri_back)) {
        $uri_back = deurl_redirect($uri_back);
    } else {
        $uri_back = site_url('tugas/manajemen_soal/' . $tugas['id']);
    }
    $data['uri_back'] = $uri_back;

    # panggil component
    $html_js = get_texteditor();
    $data['comp_js'] = $html_js;

    $CI->twig->display('edit-soal.html', $data);
}

function import_excel_soal_tugas($tugas_id)
{
    $CI =& get_instance();

    # harus admin atau pengajar
    if (!is_admin() AND !is_pengajar()) {
        $CI->session->set_flashdata('tugas', get_alert('warning', 'Akses Ditolak.'));
        redirect('tugas');
    }

    $tugas_id = (int)$tugas_id;
    $tugas    = $CI->tugas_model->retrieve($tugas_id);
    if (empty($tugas) OR $tugas['type_id'] == 1) {
        $CI->session->set_flashdata('tugas', get_alert('warning', 'Tugas Tidak Valid.'));
        redirect('tugas');
    }
    $data['tugas'] = $tugas;

    # jika sebagai pengajar, cek kepemilikan
    if (is_pengajar() AND $tugas['pengajar_id'] != get_sess_data('user', 'id')) {
        $CI->session->set_flashdata('tugas', get_alert('warning', 'Anda bukan pembuat tugas.'));
        redirect('tugas');
    }

    $data['upload_error'] = '';

    if (!empty($_FILES['userfile']['tmp_name'])) {
        $filename = $_FILES['userfile']['name'];
        $ext      = pathinfo($filename, PATHINFO_EXTENSION);
        if (!in_array($ext, array('xls')) ) {
            $data['upload_error'] = '<span class="text-error"><i class="icon-info-sign"></i> File Harus Bertipe xls.</span>';
        } else {
            $data_excel   = data_excel($_FILES['userfile']['tmp_name']);
            $daftar_kunci = array("a", "b", "c", "d", "e");

            $berhasil = 0;
            foreach ($data_excel as $row) {
                if (empty($row[0])) {
                    continue;
                }

                $pertanyaan_id = $CI->tugas_model->create_pertanyaan($row[0], $tugas['id']);

                # jika pilihan ganda
                if ($tugas['type_id'] == 3) {
                    $arr_label = array(1 => 'a', 'b', 'c', 'd', 'e');
                    $kunci     = !empty($row[6]) ? $row[6] : "A";
                    $kunci     = strtolower($kunci);
                    if (!in_array($kunci, $daftar_kunci)) {
                        $kunci = 'a';
                    }
                    $key_kunci = array_search($kunci, $arr_label);

                    for ($i = 1; $i <= 5; $i++) {
                        $CI->tugas_model->create_pilihan(
                            $pertanyaan_id,
                            !empty($row[$i]) ? $row[$i] : "",
                            ($key_kunci == $i) ? 1 : 0,
                            $i
                        );
                    }
                }

                $berhasil++;
            }

            $CI->session->set_flashdata('tugas', get_alert('success', "$berhasil soal baru berhasil diimport."));
            redirect('tugas/manajemen_soal/' . $tugas['id']);
        }
    }

    $CI->twig->display('import-excel-soal-tugas.html', $data);
}
