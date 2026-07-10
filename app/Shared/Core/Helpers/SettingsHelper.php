<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * Get a platform setting value by key.
     *
     * @param  string  $key      The setting key (e.g. 'platform_name')
     * @param  mixed   $default  Default value if the key doesn't exist
     * @return mixed
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return Setting::get($key, $default);
    }
}
