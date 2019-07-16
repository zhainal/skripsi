<?php

function autoload_link_terkait()
{
    $CI =& get_instance();

    $CI->menu->add("admin", 0, '<a href="' . site_url('plugins/link_terkait') . '"><i class="menu-icon icon-link"></i>Link Terkait</a>');
    $CI->menu->add("pengajar", 0, '<a href="' . site_url('plugins/link_terkait') . '"><i class="menu-icon icon-link"></i>Link Terkait</a>');
    $CI->menu->add("siswa", 0, '<a href="' . site_url('plugins/link_terkait') . '"><i class="menu-icon icon-link"></i>Link Terkait</a>');
}
