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
    Schema::create('bands', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('description');
        $table->text('biography')->nullable();
        $table->string('photo')->nullable();
        $table->string('background_color', 7)->default('#ffffff');
        $table->string('text_color', 7)->default('#000000');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bands');
    }
};
