<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewFormatCreateRegistrationCodesTable extends Migration
{
    public function up(): void
    {
        Schema::create('registration_codes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('code');
            $table->string('label');
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_codes');
    }
}
