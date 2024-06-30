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
        DB::statement("
        CREATE OR REPLACE VIEW `materials_view` AS
        SELECT m.id, m.secondary_code, m.registration, m.serial_number, m.description, m.value, m.group_id, g.name as group_name, m.department_id, d.name as department_name, m.status, m.year
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
        DB::statement("
        CREATE OR REPLACE VIEW `materials_view` AS
        SELECT m.id, m.registration, m.serial_number, m.description, m.value, m.group_id, g.name as group_name, m.department_id, d.name as department_name, m.status, m.year
        FROM `materials` as m
        LEFT JOIN `groups` as g ON g.id = m.group_id
        LEFT JOIN `departments` as d ON d.id = m.department_id
        WHERE m.deleted_at IS NULL
        ");
    }
};
