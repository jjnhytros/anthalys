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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->json('roles')->nullable()->default(json_encode([3]));
            $table->timestamps();
        });

        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            // $table->foreignId('region_id')->nullable()->constrained()->nullOnDelete();
            $table->string('api_token', 80)->unique()->nullable()->default(null);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username')->unique();
            $table->string('email')->nullable()->unique();
            $table->json('resources')->nullable();
            $table->json('attributes')->nullable();
            $table->decimal('cash', 24, 2);
            $table->decimal('bank', 24, 2);
            $table->string('bank_account');
            $table->boolean('have_phone')->default(true);
            $table->string('phone_number')->nullable();
            $table->integer('crafting_skill')->default(1);
            $table->integer('trading_skill')->default(1);
            $table->integer('work_level')->default(1);
            $table->integer('loyalty')->default(0);
            $table->integer('reputation')->default(0);
            $table->decimal('emergency_balance', 24, 2)->default(0);
            $table->decimal('loan_amount', 24, 2)->default(0);
            $table->decimal('loan_interest', 24, 2)->default(0);
            $table->date('loan_due_date')->nullable();
            $table->boolean(column: 'status');
            $table->boolean('is_online')->default(false);
            $table->boolean('is_npc')->default(false); // Campo per distinguere gli NPC dai giocatori
            $table->timestamps();
        });

        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained('characters')->cascadeOnDelete();
            $table->text('bio')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('link')->nullable();
            $table->json('skills')->nullable();
            $table->boolean('verified')->default(false);
            $table->enum('privacy', ['public', 'private'])->default('public');
            $table->json('preferences')->nullable();
            $table->boolean('night_mode')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('characters');
        Schema::dropIfExists('users');
    }
};
