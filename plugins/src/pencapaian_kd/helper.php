<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function kd_get_urutan_pilihan($id, $pertanyaan_id)
{
    $CI =& get_instance();

    $pilihan = $CI->tugas_model->retrieve_pilihan($id, $pertanyaan_id);
    return $pilihan['urutan'];
}
