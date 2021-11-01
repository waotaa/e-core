<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewFormatCreateClientCharacteristicInstrumentTable extends Migration
{
    public function up(): void
    {
        Schema::create('client_characteristic_instrument', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('client_characteristic_id');
            $table
                ->foreign('client_characteristic_id', 'client_char_instrument_client_char_id_foreign')
                ->references('id')
                ->on('client_characteristics')
                ->cascadeOnDelete();
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_characteristic_instrument');
    }
}
