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
        Schema::create('anthal_timezones', function (Blueprint $table) {
            $table->id();
            $table->string('identifier')->unique();
            $table->string('abbreviation', 3)->unique();
            $table->integer('offset_hours');
            $table->string('country_code')->nullable();
            $table->decimal('latitude', 12, 10);
            $table->decimal('longitude', 12, 10);
            $table->string('comments');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timezones');
    }
};
