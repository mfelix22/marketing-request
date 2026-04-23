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
        // Reset any invalid role values to 'staff' before converting to ENUM
        DB::statement("UPDATE users SET role = 'staff' WHERE role NOT IN ('admin','marcom','manager','staff','gm','director')");

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','marcom','manager','staff','gm','director') NOT NULL DEFAULT 'staff'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(255) NOT NULL DEFAULT 'staff'");
    }
};
