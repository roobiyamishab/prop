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
        Schema::create('seller_investment_listings', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Link to users table (seller / developer / promoter)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // 🔹 NEW FIELD — admin who created listing
            $table->unsignedBigInteger('created_by_admin_id')->nullable();

            $table->string('property_code', 20)->unique(); // INV100, JV100, COM100 etc.

            // Status: normal / hot / urgent / sold / booked / off_market
            $table->enum('status', ['normal', 'hot', 'urgent', 'sold', 'booked', 'off_market'])
                  ->default('normal');

            $table->string('project_name', 255);
            $table->string('project_type', 200)->nullable(); // land development, villas, etc.

            // Location
            $table->string('district', 100)->nullable();
            $table->string('micro_location', 255)->nullable();
            $table->string('landmark', 255)->nullable();
            $table->text('map_link')->nullable();

            // Investment
            $table->decimal('project_cost', 14, 2)->nullable();
            $table->decimal('investment_required', 14, 2)->nullable();
            $table->string('profit_sharing_model', 255)->nullable();
            $table->string('payback_period', 100)->nullable();

            // Project status
            $table->string('project_status', 150)->nullable();
            $table->integer('completion_percent')->nullable(); // 0–100

            // Documents
            $table->json('documents')->nullable(); // DPR, layout, govt approvals, etc.

            $table->timestamps();

            $table->index(['district', 'project_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_investment_listings');
    }
};
