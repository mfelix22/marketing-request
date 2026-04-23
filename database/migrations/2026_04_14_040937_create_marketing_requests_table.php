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
        Schema::create('marketing_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('pic_name');
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->date('date_request');
            $table->date('deadline');

            // Project For
            $table->boolean('is_local_campaign')->default(false);
            $table->boolean('is_group_campaign')->default(false);
            $table->boolean('for_sales')->default(false);
            $table->string('sales_vehicle_type')->nullable(); // passenger_car, commercial_vehicle, both
            $table->boolean('for_aftersales')->default(false);
            $table->string('aftersales_vehicle_type')->nullable();
            $table->boolean('for_others')->default(false);
            $table->string('others_description')->nullable();

            // Project Description
            $table->text('purpose');
            $table->text('detail_concept')->nullable();
            $table->text('further_information')->nullable();
            $table->string('reference_visual')->nullable(); // file path

            // Output Media (JSON array)
            $table->json('output_media')->nullable();

            // Status & Approval
            $table->string('status')->default('submitted'); // submitted, under_review, approved, rejected
            $table->text('manager_comment')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_requests');
    }
};
