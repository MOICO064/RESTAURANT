<?php
use App\Models\SystemInfo;

if (!function_exists('system_info')) {
    function system_info($key)
    {
        return SystemInfo::where('meta_field', $key)->value('meta_value');
    }
}
