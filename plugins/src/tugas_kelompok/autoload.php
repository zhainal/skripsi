<?php

function autoload_tugas_kelompok()
{
    $CI =& get_instance();

    $CI->menu->add("admin", 3, '<a href="' . site_url('plugins/tugas_kelompok?clear_filter=true') . '"><i class="menu-icon icon-tasks"></i>Tugas Kelompok</a>');
    $CI->menu->add("pengajar", 1, '<a href="' . site_url('plugins/tugas_kelompok?clear_filter=true') . '"><i class="menu-icon icon-tasks"></i>Tugas Kelompok</a>');
    $CI->menu->add("siswa", 1, '<a href="' . site_url('plugins/tugas_kelompok?clear_filter=true') . '"><i class="menu-icon icon-tasks"></i>Tugas Kelompok</a>');
}
