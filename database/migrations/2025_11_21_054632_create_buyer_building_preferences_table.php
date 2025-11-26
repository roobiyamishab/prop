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

    // 🔹 Status of this building requirement
    $table->enum('status', ['active', 'urgent', 'completed'])
          ->default('active');

    $table->json('preferred_districts')->nullable();

    $table->string('building_type', 120)->nullable(); // Hospital, Hotel, Office, etc.

    $table->integer('area_min')->nullable();          // sqft
    $table->integer('area_max')->nullable();
    $table->integer('exact_area')->nullable();

    $table->integer('frontage_min')->nullable();      // feet

    $table->string('age_preference', 100)->nullable(); // New, <5 years, etc.

    $table->decimal('total_budget_min', 14, 2)->nullable();
    $table->decimal('total_budget_max', 14, 2)->nullable();

    $table->json('micro_locations')->nullable();
    $table->string('distance_requirement', 200)->nullable();

    $table->decimal('rent_expectation', 14, 2)->nullable(); // if buyer wants rental yield

    // 🔹 Super admin who created this requirement
    $table->unsignedBigInteger('created_by_admin_id')->nullable();
    // or, if you have an admins table and want FK:
    // $table->foreignId('created_by_admin_id')->nullable()->constrained('admins')->nullOnDelete();

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
