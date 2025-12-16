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
        Schema::create('seller_land_listings', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Link to users table (seller)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Admin creator (nullable)
            $table->unsignedBigInteger('created_by_admin_id')->nullable();

            $table->string('property_code', 20)->unique(); // LND100, LND101, etc.

            // Status
            $table->enum('status', ['normal', 'hot', 'urgent', 'sold', 'booked', 'off_market'])
                  ->default('normal');

            /**
             * NEW: Location IDs (recommended)
             * Uses your package seeded tables (countries, states, cities)
             */
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable(); // city/district id from `cities` table

            // FK constraints (safe: nullable + nullOnDelete)
            $table->foreign('country_id')->references('id')->on('countries')->nullOnDelete();
            $table->foreign('state_id')->references('id')->on('states')->nullOnDelete();
            $table->foreign('district_id')->references('id')->on('cities')->nullOnDelete();

            /**
             * Old text fields (backward compatibility)
             * Keep them if your current UI/controller still sends strings.
             * You can later remove them after full migration to IDs.
             */
            $table->string('district', 120)->nullable(); // was required earlier
            $table->string('taluk', 120)->nullable();
            $table->string('village', 120)->nullable();
            $table->string('exact_location', 255)->nullable();
            $table->string('landmark', 255)->nullable();
            $table->string('survey_no', 120)->nullable();
            $table->text('google_map_link')->nullable();

            // Land
            $table->decimal('land_area', 10, 2)->nullable();
            $table->enum('land_unit', ['cent', 'acre', 'sqft'])->default('cent');
            $table->string('proximity', 150)->nullable();  // NH/SH/Town/Others
            $table->integer('road_frontage')->nullable(); // feet
            $table->string('plot_shape', 100)->nullable(); // square / rectangle / irregular

            // Zoning & legal
            $table->string('zoning_type', 120)->nullable();
            $table->string('ownership_type', 100)->nullable();
            $table->text('restrictions')->nullable();

            // Pricing
            $table->decimal('expected_price_per_cent', 14, 2)->nullable();
            $table->string('negotiability', 50)->nullable();
            $table->integer('expected_advance_pct')->nullable();
            $table->decimal('min_offer_expected', 14, 2)->nullable();
            $table->text('market_value_info')->nullable();

            // Condition
            $table->string('land_type', 100)->nullable();
            $table->string('current_use', 100)->nullable();
            $table->boolean('electricity')->default(false);
            $table->boolean('water')->default(false);
            $table->boolean('drainage')->default(false);
            $table->boolean('compound_wall')->default(false);

            // Sale timeline
            $table->string('sale_timeline', 100)->nullable();

            // Media & docs
            $table->json('photos')->nullable();
            $table->json('videos')->nullable();
            $table->json('documents')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['country_id', 'state_id', 'district_id']);
            $table->index(['district', 'zoning_type']); // keep for older records / text search
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_land_listings');
    }
};
