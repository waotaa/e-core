<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDownloadInstrumentTable extends Migration
{
    public function up(): void
    {
        Schema::create('download_instrument', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('download_id')->constrained()->cascadeOnDelete();
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('download_instrument');
    }
}
