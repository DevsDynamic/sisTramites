<?php

namespace App\Services;

use App\Models\WorkflowPermission;

class WorkflowPermissionService
{
    public function can(
        int $documentTypeId,
        int $areaId,
        string $action
    ): bool {

        $permission = WorkflowPermission::where([
            'document_type_id' => $documentTypeId,
            'area_id' => $areaId,
        ])->first();

        if (!$permission) {
            return false;
        }

        return in_array(
            $action,
            $permission->allowed_actions
        );
    }
}