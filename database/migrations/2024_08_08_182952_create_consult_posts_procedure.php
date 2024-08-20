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
            'CREATE PROCEDURE sp_post(p_id INT, p_title VARCHAR(255))
            BEGIN
	            SELECT * FROM posts p
                WHERE (p_id IS NULL OR p.id=p_id) AND (p_title IS NULL OR p.title LIKE CONCAT("%", p_title, "%") COLLATE utf8mb4_unicode_ci);
            END'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DROP PROCEDURE IF EXIST sp_post');
    }
};
