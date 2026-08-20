<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $fillable = [
        'name',
        'guard_name',
    ];

    public static function moduleLabel(string $module): string
    {
        return [
            'dashboard' => 'Panel principal',
            'users' => 'Usuarios',
            'roles' => 'Roles',
            'areas' => 'Áreas',
            'document-types' => 'Tipos de documento',
            'documents' => 'Documentos',
            'document-series' => 'Series documentales',
            'signature' => 'Firmas',
            'flows' => 'Flujos',
            'settings' => 'Configuración',
        ][$module] ?? str($module)->replace('-', ' ')->title()->toString();
    }

    public static function actionLabel(string $action): string
    {
        return [
            'view' => 'Ver',
            'view-all' => 'Ver toda la información',
            'create' => 'Crear',
            'edit' => 'Editar',
            'delete' => 'Eliminar',
            'sign' => 'Firmar',
            'manage-all' => 'Administrar todas',
            'manage-admins' => 'Administrar cuentas administradoras',
            'manage-system' => 'Administrar el sistema de roles',
        ][$action] ?? str($action)->replace('-', ' ')->title()->toString();
    }

    public function getDisplayNameAttribute(): string
    {
        [$module, $action] = array_pad(
            explode('.', $this->name, 2),
            2,
            null
        );

        return $action
            ? self::moduleLabel($module) . ' · ' . self::actionLabel($action)
            : self::moduleLabel($module);
    }
}
