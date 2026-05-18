<?php

/*
|--------------------------------------------------------------------------
| TENANT SETTINGS
|--------------------------------------------------------------------------
*/
if (! function_exists('tenant_setting')) {
    function tenant_setting($key, $default = null)
    {
        if (! tenant()) {
            return $default;
        }

        //return tenant()->settings[$key] ?? $default;
        return data_get(
            tenant()->settings,
            $key,
            $default
        );
    }
}

/*
|--------------------------------------------------------------------------
| TENANT BRANDING
|--------------------------------------------------------------------------
*/
if (! function_exists('tenant_branding')) {

    function tenant_branding($key = null, $default = null)
    {
        $tenant = tenant();

        if (! $tenant) {
            return $default;
        }

        $branding =
            data_get(
                $tenant->settings,
                'branding',
                []
            );

        if ($key) {
            return data_get(
                $branding,
                $key,
                $default
            );
        }

        return $branding;
    }
}

/*
|--------------------------------------------------------------------------
| LOGO
|--------------------------------------------------------------------------
*/
function tenant_logo()
{
    $logo =
        tenant_branding('logo');

    return $logo
        ? asset('storage/' . $logo)
        : 'https://placehold.co/200x200?text=LOGO';
}

/*
|--------------------------------------------------------------------------
| FAVICON
|--------------------------------------------------------------------------
*/
function tenant_favicon()
{
    $favicon =
        tenant_branding('favicon');

    return $favicon
        ? asset('storage/' . $favicon)
        : 'https://placehold.co/64x64?text=ICON';
}

/*
|--------------------------------------------------------------------------
| LOGIN BACKGROUND
|--------------------------------------------------------------------------
*/
function tenant_login_background()
{
    $bg =
        tenant_branding('login_background');

    return $bg
        ? asset('storage/' . $bg)
        : 'https://images.unsplash.com/photo-1521791136064-7986c2920216?q=80&w=1974';
}

/*
|--------------------------------------------------------------------------
| PRIMARY COLOR
|--------------------------------------------------------------------------
*/
function tenant_primary_color()
{
    return tenant_branding(
        'primary_color',
        '#206bc4'
    );
}

/*
|--------------------------------------------------------------------------
| SIDEBAR COLOR
|--------------------------------------------------------------------------
*/
function tenant_sidebar_color()
{
    return tenant_branding(
        'sidebar_color',
        '#111827'
    );
}