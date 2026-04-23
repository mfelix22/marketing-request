<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('marketing_requests', function (Blueprint $table) {
            // production_status is separate from the approval status:
            // pending -> on_process -> revision -> completed
            $table->string('production_status')->nullable()->after('status');
            $table->string('final_file')->nullable()->after('production_status');
            $table->text('production_notes')->nullable()->after('final_file');
            $table->timestamp('production_updated_at')->nullable()->after('production_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_requests', function (Blueprint $table) {
            $table->dropColumn(['production_status', 'final_file', 'production_notes', 'production_updated_at']);
        });
    }
};
