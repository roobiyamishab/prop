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
        Schema::create('seller_building_listings', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Link to users table (seller)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->string('property_code', 20)->unique(); // BLD100, HOS100, HTL100, etc.

            // Status: normal / hot / urgent / sold / booked / off_market
            $table->enum('status', ['normal', 'hot', 'urgent', 'sold', 'booked', 'off_market'])
                  ->default('normal');

            // Location
            $table->string('district', 120);
            $table->string('area', 120)->nullable();
            $table->string('street_name', 255)->nullable();
            $table->string('landmark', 255)->nullable();
            $table->text('map_link')->nullable();

            // Type
            $table->string('building_type', 150)->nullable();

            // Specs
            $table->decimal('total_plot_area', 12, 2)->nullable();
            $table->decimal('builtup_area', 12, 2)->nullable();
            $table->integer('floors')->nullable();
            $table->integer('construction_year')->nullable();
            $table->string('building_age', 100)->nullable();
            $table->string('structure_condition', 150)->nullable();

            $table->boolean('lift_available')->default(false);
            $table->integer('parking_slots')->nullable();
            $table->integer('road_frontage')->nullable();

            // Pricing
            $table->decimal('expected_price', 14, 2)->nullable();
            $table->decimal('price_per_sqft', 12, 2)->nullable();
            $table->string('negotiability', 100)->nullable();
            $table->integer('expected_advance_pct')->nullable();

            // Documents
            $table->json('documents')->nullable(); // permit, completion, ownership, etc.
            $table->json('photos')->nullable(); 

            // Timeline
            $table->string('sell_timeline', 100)->nullable();

            $table->timestamps();

            $table->index(['district', 'building_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_building_listings');
    }
};
