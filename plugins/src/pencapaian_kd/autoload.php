<?php

function autoload_pencapaian_kd()
{
    $CI =& get_instance();

    $CI->menu->add("admin", 3, '<a href="' . site_url('plugins/pencapaian_kd') . '"><i class="menu-icon icon-signal"></i>Pencapaian Kompetensi Dasar</a>');
    $CI->menu->add("pengajar", 1, '<a href="' . site_url('plugins/pencapaian_kd') . '"><i class="menu-icon icon-signal"></i>Pencapaian Kompetensi Dasar</a>');
}
