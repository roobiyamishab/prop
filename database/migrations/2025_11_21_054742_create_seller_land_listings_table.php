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

            // NEW FIELD — admin creator (nullable)
            $table->unsignedBigInteger('created_by_admin_id')->nullable();

            $table->string('property_code', 20)->unique(); // LND100, LND101, etc.

            // Status
            $table->enum('status', ['normal', 'hot', 'urgent', 'sold', 'booked', 'off_market'])
                  ->default('normal');

            // Location
            $table->string('district', 120);
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
            $table->index(['district', 'zoning_type']);
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
