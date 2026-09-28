<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_system')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('permissions', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('label');
            $table->string('group')->index();
            $table->timestamps();
        });

        Schema::create('permission_role', function (Blueprint $table): void {
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->primary(['permission_id', 'role_id']);
        });

        Schema::create('role_user', function (Blueprint $table): void {
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->primary(['role_id', 'user_id']);
        });

        $now = now();
        $permissionIds = [];

        foreach (config('admin_permissions.permissions', []) as $name => $permission) {
            $permissionIds[$name] = DB::table('permissions')->insertGetId([
                'name' => $name,
                'label' => $permission['label'],
                'group' => $permission['group'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $superAdminRoleId = null;

        foreach (config('admin_permissions.roles', []) as $slug => $role) {
            $roleId = DB::table('roles')->insertGetId([
                'name' => $role['name'],
                'slug' => $slug,
                'description' => $role['description'] ?? null,
                'is_system' => $slug === 'super-admin',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            if ($slug === 'super-admin') {
                $superAdminRoleId = $roleId;
            }

            $rolePermissions = $role['permissions'] === ['*'] ? array_keys($permissionIds) : $role['permissions'];

            foreach ($rolePermissions as $permissionName) {
                if (isset($permissionIds[$permissionName])) {
                    DB::table('permission_role')->insert([
                        'permission_id' => $permissionIds[$permissionName],
                        'role_id' => $roleId,
                    ]);
                }
            }
        }

        if ($superAdminRoleId) {
            $adminIds = DB::table('users')->where('is_admin', true)->pluck('id');

            foreach ($adminIds as $adminId) {
                DB::table('role_user')->insertOrIgnore([
                    'role_id' => $superAdminRoleId,
                    'user_id' => $adminId,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
