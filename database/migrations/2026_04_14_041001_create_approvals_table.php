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
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('approver_id')->constrained('users')->cascadeOnDelete();
            $table->string('status'); // approved, rejected
            $table->text('comment')->nullable();
            $table->timestamp('acted_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
