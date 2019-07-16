<?php

function lt_src_master($act = "", $new_value = "")
{
    $CI =& get_instance();

    $field_id   = "link_terkait";
    $field_name = "Daftar Link Terkait";
    $retrieve   = retrieve_field($field_id);
    if (empty($retrieve)) {
        create_field($field_id, $field_name, "");
    }
    $retrieve = retrieve_field($field_id);

    switch ($act) {
        case 'update':
            update_field($field_id, $field_name, $new_value);
        break;

        default:
            return $retrieve;
        break;
    }
}

function lt_retrieve_all()
{
    $CI =& get_instance();

    $master = lt_src_master();
    $decode_value = json_decode($master['value'], 1);
    return $decode_value;
}

function lt_retrieve($key)
{
    $CI =& get_instance();

    $master = lt_src_master();
    $decode_value = json_decode($master['value'], 1);
    return $decode_value[$key];
}

function lt_create($link, $label = "")
{
    $CI =& get_instance();

    $master = lt_src_master();
    $decode_value = json_decode($master['value'], 1);
    $decode_value[] = array('link' => $link, 'label' => $label);

    lt_src_master('update', json_encode($decode_value));

    return true;
}

function lt_update($key, $link, $label = "")
{
    $CI =& get_instance();

    $master = lt_src_master();
    $decode_value = json_decode($master['value'], 1);
    $decode_value[$key] = array('link' => $link, 'label' => $label);

    lt_src_master('update', json_encode($decode_value));

    return true;
}

function lt_delete($key)
{
    $CI =& get_instance();

    $master = lt_src_master();
    $decode_value = json_decode($master['value'], 1);
    unset($decode_value[$key]);

    lt_src_master('update', json_encode($decode_value));

    return true;
}