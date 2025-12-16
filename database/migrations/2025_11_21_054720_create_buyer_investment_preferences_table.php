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
        Schema::create('buyer_investment_preferences', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Link to users table (buyer)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Status of this investment requirement
            $table->enum('status', ['active', 'urgent', 'completed'])
                  ->default('active');

            /*
             * Location hierarchy (same pattern as land / building)
             *
             * preferred_countries => ["India", "UAE"]
             * preferred_states    => ["Kerala"]
             * preferred_districts => ["Ernakulam", "Kozhikode"]
             */
            $table->json('preferred_countries')->nullable();
            $table->json('preferred_states')->nullable();
            $table->json('preferred_districts')->nullable();

            // More granular free-text micro-locations
            $table->json('preferred_locations')->nullable();

            // Land / Rental buildings / Villas / Flats / Hospital / Any
            $table->string('investment_property_type', 150)->nullable();

            $table->decimal('investment_budget_min', 14, 2)->nullable();
            $table->decimal('investment_budget_max', 14, 2)->nullable();

            $table->decimal('profit_expectation_year', 14, 2)->nullable(); // expected profit/year

            // Super admin who created this investment requirement
            $table->unsignedBigInteger('created_by_admin_id')->nullable();
            // Or FK:
            // $table->foreignId('created_by_admin_id')->nullable()->constrained('admins')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyer_investment_preferences');
    }
};
