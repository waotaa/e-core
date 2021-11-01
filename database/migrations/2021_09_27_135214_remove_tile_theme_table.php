<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveTileThemeTable extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('tile_theme');
    }

    public function down(): void
    {
        Schema::create('tile_theme', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('tile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('theme_id')->constrained()->cascadeOnDelete();
        });
    }
}
