<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('permissions') || ! Schema::hasTable('roles')) {
            return;
        }

        $now = now();
        $permissionIds = [];

        foreach (config('admin_permissions.permissions', []) as $name => $permission) {
            $permissionId = DB::table('permissions')->where('name', $name)->value('id');

            if ($permissionId) {
                DB::table('permissions')->where('id', $permissionId)->update([
                    'label' => $permission['label'],
                    'group' => $permission['group'],
                    'updated_at' => $now,
                ]);
            } else {
                $permissionId = DB::table('permissions')->insertGetId([
                    'name' => $name,
                    'label' => $permission['label'],
                    'group' => $permission['group'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $permissionIds[$name] = $permissionId;
        }

        foreach (config('admin_permissions.roles', []) as $slug => $role) {
            $roleId = DB::table('roles')->where('slug', $slug)->value('id');

            if (! $roleId) {
                $roleId = DB::table('roles')->insertGetId([
                    'name' => $role['name'],
                    'slug' => $slug,
                    'description' => $role['description'] ?? null,
                    'is_system' => $slug === 'super-admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            } else {
                DB::table('roles')->where('id', $roleId)->update([
                    'name' => $role['name'],
                    'description' => $role['description'] ?? null,
                    'updated_at' => $now,
                ]);
            }

            $rolePermissions = $role['permissions'] === ['*'] ? array_keys($permissionIds) : $role['permissions'];
            $syncIds = collect($rolePermissions)
                ->map(fn (string $permissionName) => $permissionIds[$permissionName] ?? null)
                ->filter()
                ->values();

            DB::table('permission_role')->where('role_id', $roleId)->delete();

            foreach ($syncIds as $permissionId) {
                DB::table('permission_role')->insert([
                    'permission_id' => $permissionId,
                    'role_id' => $roleId,
                ]);
            }
        }
    }

    public function down(): void
    {
        //
    }
};
