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

            // 🔹 Status of this investment requirement
            // active    = currently looking for this investment
            // urgent    = high-priority requirement
            // completed = deal closed / requirement fulfilled
            $table->enum('status', ['active', 'urgent', 'completed'])
                  ->default('active');

            $table->json('preferred_districts')->nullable();
            $table->json('preferred_locations')->nullable();

            // Land / Rental buildings / Villas / Flats / Hospital / Any other
            $table->string('investment_property_type', 150)->nullable();

            $table->decimal('investment_budget_min', 14, 2)->nullable();
            $table->decimal('investment_budget_max', 14, 2)->nullable();

            $table->decimal('profit_expectation_year', 14, 2)->nullable(); // expected profit/year

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
