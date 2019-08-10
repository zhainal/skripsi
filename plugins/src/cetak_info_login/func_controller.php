<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function construct()
{
    # cek session
    must_login();

    # jika bukan admin
    if (!is_admin()) {
        show_error("Akses ditolak");
    }
}

function index()
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

        # jika nis tidak kosong, password diubah ke nis
        if (!empty($val['nis'])) {
            $CI->login_model->update_password($val['login']['id'], $val['nis']);
            $val['login']['password'] = $val['nis'];
        } else {
            $CI->login_model->update_password($val['login']['id'], "12345");
            $val['login']['password'] = "12345";
        }
    }

    $data['siswas']    = $retrieve_all;
    $data['jml_siswa'] = count($retrieve_all);
    $data['filter']    = $filter;
    $data['kelas_all'] = $CI->kelas_model->retrieve_all_child(true);

    $CI->twig->display('cetak-info-login-siswa.html', $data);
}