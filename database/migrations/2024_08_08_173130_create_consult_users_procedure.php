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
        DB::unprepared(
            'CREATE PROCEDURE sp_user(p_id INT, p_name VARCHAR(255))
            BEGIN
	            SELECT * FROM users u
                WHERE (p_id IS NULL OR u.id=p_id) AND (p_name IS NULL OR u.name LIKE CONCAT("%", p_name, "%") COLLATE utf8mb4_unicode_ci);
            END'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXIST sp_user');
    }
};
