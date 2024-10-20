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
        Schema::create('anthal_provinces', function (Blueprint $table) {
            $table->id();
            $table->string('province', 36);
            $table->string('full_name', 48);
            $table->string('form', 24);
            $table->string('state', 24);
            $table->string('color', 7);
            $table->string('capital', 36)->nullable();
            $table->bigInteger('area_km2');
            $table->bigInteger('population_total');
            $table->bigInteger('population_rural');
            $table->bigInteger('population_urban');
            $table->integer('burgs');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provinces');
    }
};
