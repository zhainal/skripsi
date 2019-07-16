<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function construct()
{
    # cek session
    must_login();

    # jika bukan admin
    if (!is_admin()) {
        show_error("Akses Ditolak");
    }
}

function import_excel()
{
    $CI =& get_instance();

    # smbil semua kelas
    $kelas = $CI->kelas_model->retrieve_all_child();
    $data['kelas'] = $kelas;

    $CI->form_validation->set_rules('kelas_id', 'Kelas', 'required');
    $CI->form_validation->set_rules('default_username', 'Domain Username', 'required|callback_domain_check');

    $data['error_domain'] = "";
    $data['error_upload'] = "";
    if ($CI->form_validation->run() == true) {
        $kelas_id        = $CI->input->post('kelas_id', true);
        $domain_username = $CI->input->post('default_username', true);

        # check domain username
        if (!filter_var('almazari@' . $domain_username, FILTER_VALIDATE_EMAIL)) {
            $data['error_domain'] = '<span class="text-error"><i class="icon-info-sign"></i> Domain Username tidak valid.</span>';
        }

        if (empty($_FILES['userfile']['tmp_name'])) {
            $data['error_upload'] = '<span class="text-error"><i class="icon-info-sign"></i> File Dibutuhkan.</span>';
        } else {
            $filename = $_FILES['userfile']['name'];
            $ext      = pathinfo($filename, PATHINFO_EXTENSION);
            if (!in_array($ext, array('xls')) ) {
                $data['error_upload'] = '<span class="text-error"><i class="icon-info-sign"></i> File Harus Bertipe xls.</span>';
            }
        }

        if (empty($data['error_domain']) AND empty($data['error_upload'])) {
            $data_excel = data_excel($_FILES['userfile']['tmp_name']);
            foreach ($data_excel as $row) {
                # jika nis kosong
                if (empty($row[0])) {
                    continue;
                }

                # setup tgl lahir
                $split_tgl = explode('/', trim($row[4]));
                $tanggal_lahir = $split_tgl[2] . '-' . $split_tgl[1] . '-' . $split_tgl[0];

                # setup jenis kelamin
                if (in_array(strtolower(trim($row[2])), array('l', 'p'))) {
                    $jenis_kelamin = (strtolower(trim($row[2])) == 'l') ? 'Laki-laki' : 'Perempuan';
                }

                $nis           = trim($row[0]);
                $nama          = trim($row[1]);
                $tahun_masuk   = trim($row[7]);
                $tempat_lahir  = trim($row[3]);
                $agama         = strtoupper(trim($row[6]));
                $alamat        = trim($row[5]);
                $username      = trim($row[0]) . '@' . $domain_username;
                if (empty($row[8])) {
                    $password = md5($nis);
                } else {
                    $password = $row[8];
                }

                # simpan data siswa
                $siswa_id = $CI->siswa_model->create(
                    $nis,
                    $nama,
                    $jenis_kelamin,
                    $tempat_lahir,
                    $tanggal_lahir,
                    $agama,
                    $alamat,
                    $tahun_masuk,
                    null,
                    1
                );

                # simpan data login
                $CI->db->insert('login', array(
                    'username' => $username,
                    'password' => $password,
                    'siswa_id' => $siswa_id,
                    'is_admin' => 0
                ));

                # simpan kelas siswa
                $CI->kelas_model->create_siswa(
                    $kelas_id,
                    $siswa_id,
                    1
                );
            }

            @unlink($_FILES['userfile']['tmp_name']);

            $CI->session->set_flashdata('siswa', get_alert('success', 'Import Data Siswa Berhasil.'));
            redirect('siswa/index/1');
        }
    }

    $CI->twig->display('import-ecxel-siswa.html', $data);
}

function export_excel()
{
    $CI =& get_instance();

    $filter = $CI->session->userdata('filter_siswa');

    if (empty($filter)) {
        $filter = array(
            'nis'           => '',
            'nama'          => '',
            'jenis_kelamin' => '',
            'tahun_masuk'   => '',
            'tempat_lahir'  => '',
            'tgl_lahir'     => '',
            'bln_lahir'     => '',
            'thn_lahir'     => '',
            'agama'         => '',
            'alamat'        => '',
            'status_id'     => '',
            'kelas_id'      => '',
            'username'      => ''
        );
    }

    if (empty($filter['status_id'])) {
        $filter['status_id'] = array(1, 2);
    }

    $retrieve_all = $CI->siswa_model->retrieve_all_filter(
        $filter['nis'], $filter['nama'], $filter['jenis_kelamin'], $filter['tahun_masuk'], $filter['tempat_lahir'], $filter['tgl_lahir'], $filter['bln_lahir'], $filter['thn_lahir'], $filter['alamat'], $filter['agama'], $filter['kelas_id'], $filter['status_id'], $filter['username'], 1, false
    );

    # dapatkan data2 siswa
    foreach ($retrieve_all as $key => &$val) {
        $kelas_siswa = $CI->kelas_model->retrieve_siswa(null, array(
            'siswa_id' => $val['id'],
            'aktif'    => 1
        ));

        # kelas aktif
        if (!empty($kelas_siswa) AND $val['status_id'] != 3) {
            $kelas              = $CI->kelas_model->retrieve($kelas_siswa['kelas_id']);
            $val['kelas_aktif'] = $kelas;
        }

        # login
        $val['login'] = $CI->login_model->retrieve(null, null, null, $val['id']);
    }

    $data['siswas']    = $retrieve_all;
    $data['filter']    = $filter;
    $data['kelas_all'] = $CI->kelas_model->retrieve_all_child(true);

    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=export-siswa.xls");
    $CI->twig->display('export-excel-filter-siswa.html', $data);
}