<?php
use App\Services\TenantService;

// function tenant_id()
// {
//     return TenantService::id();
// }

function tenant_id()
{
    return tenant()?->id;
}

function user_id()
{
    return auth()->id();
}