<?php

use App\Models\Setting;

if (!function_exists('setting')) {

    function setting()
    {
        return Setting::first()
            ?? new Setting();
    }
}

if (!function_exists('primary_color')) {

    function primary_color()
    {
        return setting()->primary_color
            ?: '#206bc4';
    }
}

if (!function_exists('sidebar_color')) {

    function sidebar_color()
    {
        return setting()->sidebar_color
            ?: '#111827';
    }
}

if (!function_exists('sidebar_text_color')) {

    function sidebar_text_color()
    {
        return setting()->sidebar_text_color
            ?: '#ffffff';
    }
}

if (!function_exists('hex_to_rgb')) {

    function hex_to_rgb($hex)
    {
        $hex = str_replace('#', '', $hex);

        return implode(',', [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ]);
    }
}

if (!function_exists('logo_url')) {

    function logo_url()
    {
        return setting()->logo
            ? asset('storage/' . setting()->logo)
            : asset('images/placeholders/logo.png');
    }
}

if (!function_exists('favicon_url')) {

    function favicon_url()
    {
        return setting()->favicon
            ? asset('storage/' . setting()->favicon)
            : asset('images/placeholders/favicon.png');
    }
}

if (!function_exists('login_background_url')) {

    function login_background_url()
    {
        return setting()->login_background
            ? asset('storage/' . setting()->login_background)
            : asset('images/placeholders/login-bg.jpg');
    }
}
