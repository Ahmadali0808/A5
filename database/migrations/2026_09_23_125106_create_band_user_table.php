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
    Schema::create('band_user', function (Blueprint $table) {
        $table->id();
        $table->foreignId('band_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->unique(['band_id', 'user_id']);
    });
}

public function down(): void
{
    Schema::dropIfExists('band_user');
}
};
