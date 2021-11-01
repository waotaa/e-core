<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewFormatCreateAvailableRegionInstrumentTable extends Migration
{
    public function up(): void
    {
        Schema::create('available_region_instrument', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('region_id')->constrained()->cascadeOnDelete();
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('available_region_instrument');
    }
}
