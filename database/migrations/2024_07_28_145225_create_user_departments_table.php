<?php

use App\Models\User;
use App\Models\UserDepartment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_departments', function (Blueprint $table) {
            $table->foreignId('department_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });

        $users = User::all();

        foreach ($users as $user) {
            if ($user->department_id) {
                $userDepartment = UserDepartment::create([
                    'user_id' => $user->id,
                    'department_id' => $user->department_id,
                ]);
                $userDepartment->save();
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('department_id');
        });

        DB::statement("
        CREATE OR REPLACE VIEW `users_view` AS
        SELECT 
            u.id, 
            u.name, 
            u.email, 
            mr.role_id, 
            r.name as type, 
        GROUP_CONCAT(dpt.name ORDER BY dpt.name SEPARATOR ', ') as departments
        FROM users as u
        LEFT JOIN model_has_roles as mr ON mr.model_id = u.id
        LEFT JOIN roles as r ON r.id = mr.role_id
        LEFT JOIN user_departments as d ON d.user_id = u.id
        LEFT JOIN departments as dpt ON dpt.id = d.department_id
        WHERE u.deleted_at IS NULL AND d.deleted_at IS NULL 
        GROUP BY u.id, u.name, u.email, mr.role_id, r.name;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::dropIfExists('user_departments');

        DB::statement('
        CREATE OR REPLACE VIEW `users_view` AS
        SELECT u.id, u.name, u.email, mr.role_id, r.name as type, u.department_id, d.name as department
        FROM users as u
        LEFT JOIN model_has_roles as mr ON mr.model_id = u.id
        LEFT JOIN roles as r ON r.id = mr.role_id
        LEFT JOIN departments as d ON d.id = u.department_id
        WHERE u.deleted_at IS NULL
        ');
    }
};
