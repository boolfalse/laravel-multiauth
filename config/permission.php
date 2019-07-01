<?php

return [
    'models' => [
        'permission' => Spatie\Permission\Models\Permission::class,
        'role' => Spatie\Permission\Models\Role::class,
    ],
    'table_names' => [
        'roles' => 'roles',
        'permissions' => 'permissions',
        'model_has_permissions' => 'model_has_permissions',
        'model_has_roles' => 'model_has_roles',
        'role_has_permissions' => 'role_has_permissions',
    ],
    'column_names' => [
        'model_morph_key' => 'model_id',
    ],
    'display_permission_in_exception' => false,

    'cache' => [
        'expiration_time' => 24 * 60, // \DateInterval::createFromDateString('24 hours'),
        'key' => 'spatie.permission.cache',
        'model_key' => 'name',
        'store' => 'default',
    ],
];
