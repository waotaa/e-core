<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveInstrumentTownshipTable extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('instrument_township');
    }

    public function down(): void
    {
        Schema::create('instrument_township', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('township_id')->constrained()->cascadeOnDelete();
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
        });
    }
}
