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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('registration')->nullable();
            $table->string('secondary_code')->nullable();
            $table->string('serial_number')->nullable();
            $table->text('description')->nullable();
            $table->longText('observations')->nullable();
            $table->decimal('value', 11, 2)->default(0);
            $table->string('status')->nullable();
            $table->foreignId('group_id')->nullable()
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('department_id')->nullable()
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

        DB::statement("
        CREATE OR REPLACE VIEW `materials_view` AS
        SELECT m.id, m.registration, m.serial_number, m.description, m.value, m.group_id, g.name as group_name, m.department_id, d.name as department_name, m.status
        FROM `materials` as m
        LEFT JOIN `groups` as g ON g.id = m.group_id
        LEFT JOIN `departments` as d ON d.id = m.department_id
        WHERE m.deleted_at IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW materials_view");
        Schema::dropIfExists('materials');
    }
};
