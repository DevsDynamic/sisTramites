<?php
use App\Services\TenantService;

function tenant_id()
{
    return TenantService::id();
}