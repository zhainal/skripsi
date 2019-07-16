<?php

function autoload_custom_tugas()
{
    if (is_ajax()) {
        return true;
    }

    /**
     * eksekusi cron
     */
    plugin_helper('custom_tugas', 'ct_cron', array('execute'));
}
