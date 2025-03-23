<?php

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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });

        DB::statement('
        CREATE OR REPLACE VIEW `users_view` AS
        SELECT u.id, u.name, u.email, mr.role_id, r.name as type
        FROM users as u
        LEFT JOIN model_has_roles as mr ON mr.model_id = u.id
        LEFT JOIN roles as r ON r.id = mr.role_id
        WHERE u.deleted_at IS NULL
        ');
    }
};
