<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->morphs('addressable');

            $table->string('straatnaam')->nullable();
            $table->string('huisnummer')->nullable();
            $table->integer('postbusnummer')->nullable();
            $table->integer('antwoordnummer')->nullable();
            $table->string('postcode');
            $table->string('woonplaats');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
}
