<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

return new class extends Migration
{
    public function up()
    {
        // 1. Backup semua data yang ada
        $existingRoles = DB::table('roles')->get();
        $existingPermissions = DB::table('permissions')->get();
        $existingModelHasRoles = DB::table('model_has_roles')->get();
        $existingModelHasPermissions = DB::table('model_has_permissions')->get();
        $existingRoleHasPermissions = DB::table('role_has_permissions')->get();

        // 2. Drop semua dependent tables
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('model_has_permissions');
        Schema::dropIfExists('role_has_permissions');

        // 3. Drop dan recreate tabel roles dengan UUID
        Schema::dropIfExists('roles');
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['name', 'guard_name']);
        });

        // 4. Drop dan recreate tabel permissions dengan UUID
        Schema::dropIfExists('permissions');
        Schema::create('permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['name', 'guard_name']);
        });

        // 5. Insert data roles dengan UUID baru
        $roleMapping = [];
        foreach ($existingRoles as $role) {
            $newId = Uuid::uuid4()->toString();
            $roleMapping[$role->id] = $newId;
            DB::table('roles')->insert([
                'id' => $newId,
                'name' => $role->name,
                'guard_name' => $role->guard_name,
                'created_at' => $role->created_at,
                'updated_at' => $role->updated_at,
                'deleted_at' => null,
            ]);
        }

        // 6. Insert data permissions dengan UUID baru
        $permissionMapping = [];
        foreach ($existingPermissions as $permission) {
            $newId = Uuid::uuid4()->toString();
            $permissionMapping[$permission->id] = $newId;
            DB::table('permissions')->insert([
                'id' => $newId,
                'name' => $permission->name,
                'guard_name' => $permission->guard_name,
                'created_at' => $permission->created_at,
                'updated_at' => $permission->updated_at,
                'deleted_at' => null,
            ]);
        }

        // 7. Recreate model_has_roles dengan SEMUA UUID
        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->uuid('role_id');
            $table->string('model_type');
            $table->uuid('model_id'); // UUID karena User juga pakai UUID
            
            $table->primary(['role_id', 'model_id', 'model_type']);
            $table->index(['model_id', 'model_type']);
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        // 8. Recreate model_has_permissions dengan SEMUA UUID
        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->uuid('permission_id');
            $table->string('model_type');
            $table->uuid('model_id'); // UUID karena User juga pakai UUID
            
            $table->primary(['permission_id', 'model_id', 'model_type']);
            $table->index(['model_id', 'model_type']);
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
        });

        // 9. Recreate role_has_permissions dengan SEMUA UUID
        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->uuid('role_id');
            $table->uuid('permission_id');
            $table->primary(['role_id', 'permission_id']);
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
        });

        // 10. Insert data ke model_has_roles dengan UUID baru
        foreach ($existingModelHasRoles as $record) {
            if (isset($roleMapping[$record->role_id])) {
                DB::table('model_has_roles')->insert([
                    'role_id' => $roleMapping[$record->role_id],
                    'model_type' => $record->model_type,
                    'model_id' => $record->model_id, // model_id sudah UUID, biarkan asli
                ]);
            }
        }

        // 11. Insert data ke model_has_permissions dengan UUID baru
        foreach ($existingModelHasPermissions as $record) {
            if (isset($permissionMapping[$record->permission_id])) {
                DB::table('model_has_permissions')->insert([
                    'permission_id' => $permissionMapping[$record->permission_id],
                    'model_type' => $record->model_type,
                    'model_id' => $record->model_id, // model_id sudah UUID, biarkan asli
                ]);
            }
        }

        // 12. Insert data ke role_has_permissions dengan kedua UUID baru
        foreach ($existingRoleHasPermissions as $record) {
            if (isset($roleMapping[$record->role_id]) && isset($permissionMapping[$record->permission_id])) {
                DB::table('role_has_permissions')->insert([
                    'role_id' => $roleMapping[$record->role_id],
                    'permission_id' => $permissionMapping[$record->permission_id],
                ]);
            }
        }
    }

    public function down()
    {
        // Untuk development, kita bisa rollback dengan menghapus tabel
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('model_has_permissions');
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        
        // Note: Data akan hilang, backup dulu jika penting
    }
};