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
        Schema::create('buyer_land_preferences', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Link to users table (buyer)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // 🔹 Status of this requirement
            // active   = currently searching
            // urgent   = high-priority / immediate requirement
            // completed = requirement fulfilled / closed
            $table->enum('status', ['active', 'urgent', 'completed'])
                  ->default('active');

            $table->json('preferred_districts')->nullable();
            $table->json('preferred_locations')->nullable();

            // 👇 NEW: Land size unit (cent/acre)
            $table->string('land_size_unit', 20)->default('cent');

            $table->decimal('land_size_needed_min', 10, 2)->nullable();
            $table->decimal('land_size_needed_max', 10, 2)->nullable();

            $table->decimal('budget_per_cent_min', 12, 2)->nullable();
            $table->decimal('budget_per_cent_max', 12, 2)->nullable();

            $table->string('zoning_preference', 100)->nullable();
            $table->string('timeline_to_purchase', 100)->nullable();
            $table->string('mode_of_purchase', 50)->nullable();   // outright / loan / JV
            $table->integer('advance_capacity')->nullable();      // %
            $table->string('documentation_speed', 50)->nullable(); // fast / medium / slow
            $table->string('property_condition', 100)->nullable();

            $table->json('amenities_preference')->nullable();

            // 👇 RENAMED: use same name as in controller/view
            $table->json('infra_preference')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyer_land_preferences');
    }
};
