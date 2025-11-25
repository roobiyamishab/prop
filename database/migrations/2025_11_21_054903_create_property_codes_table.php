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
        Schema::create('property_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('prefix', 10);   // LND / BLD / VLA / HOS / HTL / INV / etc.
            $table->integer('number');      // 100, 101, 102...
            $table->timestamps();

            $table->unique(['prefix', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_codes');
    }
};
