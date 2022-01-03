<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveInstrumentThemeTable extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('instrument_theme');
    }

    public function down(): void
    {
        Schema::create('instrument_theme', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('theme_id')->constrained()->cascadeOnDelete();
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
        });
    }
}
