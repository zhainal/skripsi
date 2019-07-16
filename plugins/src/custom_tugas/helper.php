<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

# custom_tugas_plugins
function get_pengaturan_tugas($tugas_id, $index = "")
{
    $result = array(
        'max_jml_soal'           => '',
        'model_urutan_soal'      => '',
        'tampil_soal_perhalaman' => '',
        'tampil_nilai_kesiswa'   => '1',
    );

    $pengaturan_tugas = retrieve_field('pengaturan-tugas-' . $tugas_id);
    if (!empty($pengaturan_tugas['value'])) {
        $value_pengaturan_tugas = json_decode($pengaturan_tugas['value'], 1);
        $result = array_merge($result, $value_pengaturan_tugas);
    }

    return (!empty($index) AND isset($result[$index])) ? $result[$index] : $result;
}

function ct_tampil_nilai_kesiswa($tugas_id, $tugas_aktif, $sudah_mengerjakan)
{
    $pengaturan = get_pengaturan_tugas($tugas_id, 'tampil_nilai_kesiswa');

    $tampilkan = false;
    if ($pengaturan == 1) {
        if ($tugas_aktif == 0) {
            $tampilkan = true;
        }
    } elseif ($pengaturan == 2) {
        if ($sudah_mengerjakan == true) {
            $tampilkan = true;
        }
    }

    return $tampilkan;
}

function ct_option_tampil_nilai_kesiswa($status_id = null)
{
    $data = array(
        1 => "Setelah status tugas ditutup",
        2 => "Setelah selesai mengerjakan / jika nilai sudah tersedia",
        3 => "Jangan tampilkan",
    );

    return empty($status_id) ? $data : $data[$status_id];
}

function ct_validate_datetime($datetime = "")
{
    if (preg_match('/^(?:199[0-9]|20[0-9][0-9])-(?:0[1-9]|1[0-2])-(?:[0-2][0-9]|3[0-1]) (?:[0-1][0-9]|2[0-3]):[0-5][0-9]$/', $datetime)) {
        return true;
    }

    return false;
}

function ct_cron($act, $params = array())
{
    switch ($act) {
        case 'register_action_tutup':
        case 'register_action_terbitkan':
            $field_id = "ct-cron-" . $act;
            $retrieve = retrieve_field($field_id);
            if (!empty($retrieve['value'])) {
                $retrieve_value = json_decode($retrieve['value'], 1);
            } else {
                $retrieve_value = array();
            }

            $retrieve_value[$params['tugas_id']] = $params['date'];

            if (empty($retrieve)) {
                create_field($field_id, $act, json_encode($retrieve_value));
            } else {
                update_field($field_id, $act, json_encode($retrieve_value));
            }
        break;

        case 'execute':
            $CI =& get_instance();

            /**
             * supaya g boros query, dijalankan per menit
             */
            $ok_run    = false;
            $last_call = $CI->session->userdata('last_call_ct_cron');
            if (empty($last_call)) {
                $ok_run = true;
            } else {
                if (strtotime("+1 minutes", $last_call) <= time()) {
                    $ok_run = true;
                }
            }

            if (!$ok_run) {
                return true;
            }

            # update last_call
            $CI->session->set_userdata('last_call_ct_cron', time());

            /**
             * Ambil aksi terbitkan
             */
            $field_id = "ct-cron-register_action_terbitkan";
            $retrieve = retrieve_field($field_id);
            if (!empty($retrieve['value'])) {
                $retrieve_value = json_decode($retrieve['value'], 1);
                foreach ($retrieve_value as $tugas_id => $date) {
                    if (empty($date)) {
                        unset($retrieve_value[$tugas_id]);
                        continue;
                    }

                    # jika <= tgl sekarang
                    if (strtotime($date) <= time()) {
                        # update status tugas
                        $CI->tugas_model->terbitkan($tugas_id);

                        # kalo sudah dieksekusi dihapus
                        unset($retrieve_value[$tugas_id]);
                    }
                }

                update_field($field_id, "register_action_terbitkan", json_encode($retrieve_value));
            }

            /**
             * Ambil aksi tutup
             */
            $field_id = "ct-cron-register_action_tutup";
            $retrieve = retrieve_field($field_id);
            if (!empty($retrieve['value'])) {
                $retrieve_value = json_decode($retrieve['value'], 1);
                foreach ($retrieve_value as $tugas_id => $date) {
                    if (empty($date)) {
                        unset($retrieve_value[$tugas_id]);
                        continue;
                    }

                    # jika <= tgl sekarang
                    if (strtotime($date) <= time()) {
                        # update status tugas
                        $CI->tugas_model->tutup($tugas_id);

                        # kalo sudah dieksekusi dihapus
                        unset($retrieve_value[$tugas_id]);
                    }
                }

                update_field($field_id, "register_action_tutup", json_encode($retrieve_value));
            }

            # update last_call
            $CI->session->set_userdata('last_call_ct_cron', time());
        break;
    }
}
