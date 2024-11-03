<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_meal","view_any_meal","create_meal","update_meal","restore_meal","restore_any_meal","replicate_meal","reorder_meal","delete_meal","delete_any_meal","force_delete_meal","force_delete_any_meal","view_meal::serve","view_any_meal::serve","create_meal::serve","update_meal::serve","restore_meal::serve","restore_any_meal::serve","replicate_meal::serve","reorder_meal::serve","delete_meal::serve","delete_any_meal::serve","force_delete_meal::serve","force_delete_any_meal::serve","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_student","view_any_student","create_student","update_student","restore_student","restore_any_student","replicate_student","reorder_student","delete_student","delete_any_student","force_delete_student","force_delete_any_student","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user","widget_Stats","widget_CountMonthly","widget_FailureReasons","widget_CountDaily","widget_LatestServes"]},{"name":"admin","guard_name":"web","permissions":["view_meal","view_any_meal","create_meal","update_meal","reorder_meal","view_meal::serve","view_any_meal::serve","view_student","view_any_student","create_student","update_student","reorder_student","view_user","view_any_user","create_user","update_user","reorder_user"]},{"name":"cashier","guard_name":"web","permissions":["view_meal","view_any_meal"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
