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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // ADMIN, VIEWER; EDITOR
            $table->string('label')->nullable();
            $table->timestamps();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete;
            $table->foreignId('user_id')->constrained()->cascadeOnDelete;
            $table->unique(['role_id', 'user_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
    }
};
