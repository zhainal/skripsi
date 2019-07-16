<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function construct()
{
    # cek session
    must_login();

    # include model
    include 'func_model.php';
}

function index($mode = "", $key = "")
{
    $CI =& get_instance();

    # ambil semua link
    $retrieve_all  = lt_retrieve_all();
    $data['links'] = $retrieve_all;

    switch ($mode) {
        case 'delete':
            if (is_admin()) {
                lt_delete($key);

                $CI->session->set_flashdata('link', get_alert('success', 'Link Berhasil Dihapus.'));
                redirect('plugins/link_terkait/index');
            }
        break;

        case 'edit':
            if (is_admin()) {
                $data['key']  = $key;
                $data['l']    = $retrieve_all[$key];
                $data['mode'] = "edit";

                $CI->form_validation->set_rules('url', 'Url', 'required|trim');
                $CI->form_validation->set_rules('label', 'Label', 'trim');
                if ($CI->form_validation->run() == true) {
                    $link  = prep_url($CI->input->post('url', true));
                    $label = $CI->input->post('label', true);

                    lt_update($key, $link, $label);

                    $CI->session->set_flashdata('link', get_alert('success', 'Link Berhasil Diperbaharui.'));
                    redirect('plugins/link_terkait/index');
                }
            }
        break;

        default:
            if (is_admin()) {
                $CI->form_validation->set_rules('url', 'Url', 'required|trim');
                $CI->form_validation->set_rules('label', 'Label', 'trim');
                if ($CI->form_validation->run() == true) {
                    $link  = prep_url($CI->input->post('url', true));
                    $label = $CI->input->post('label', true);

                    lt_create($link, $label);

                    $CI->session->set_flashdata('link', get_alert('success', 'Link Berhasil Ditambahkan.'));
                    redirect('plugins/link_terkait/index');
                }

                $data['mode'] = "add";
            }
        break;
    }

    $CI->twig->display('list-link-terkait.html', $data);
}
