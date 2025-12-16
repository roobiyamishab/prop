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
        Schema::create('buyer_building_preferences', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Link to users table (buyer)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Status of this building requirement
            $table->enum('status', ['active', 'urgent', 'completed'])
                  ->default('active');

            /*
             * Location hierarchy (same pattern as buyer_land_preferences)
             * Store arrays of IDs (from your countries / states / cities tables):
             *   preferred_countries => [1, 101]
             *   preferred_states    => [501, 502]
             *   preferred_districts => [9001, 9002]   // city/district IDs
             */
            $table->json('preferred_countries')->nullable();
            $table->json('preferred_states')->nullable();
            $table->json('preferred_districts')->nullable();

            // Optional: extra text-based / micro areas
            $table->json('micro_locations')->nullable();

            // Building type – Hospital, Hotel, Office, etc.
            $table->string('building_type', 120)->nullable();

            // Built-up area (sqft)
            $table->integer('area_min')->nullable();
            $table->integer('area_max')->nullable();
            $table->integer('exact_area')->nullable();

            // Frontage (ft)
            $table->integer('frontage_min')->nullable();

            // Age of building acceptable – "New", "5–10 years", etc.
            $table->string('age_preference', 100)->nullable();

            // Budget range
            $table->decimal('total_budget_min', 14, 2)->nullable();
            $table->decimal('total_budget_max', 14, 2)->nullable();

            // Distance requirements (comma-separated flags like nearHighway, nearITPark, etc.)
            $table->string('distance_requirement', 200)->nullable();

            // Rent expectation (if looking for rental asset)
            $table->decimal('rent_expectation', 14, 2)->nullable();

            // Super admin who created this requirement (optional)
            $table->foreignId('created_by_admin_id')
                  ->nullable()
                  ->constrained('admins')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyer_building_preferences');
    }
};
