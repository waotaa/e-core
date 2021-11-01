<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
            $table->foreignId('professional_id')->constrained()->cascadeOnDelete();

            $table->string('author')->nullable();
            $table->string('email');
            $table->tinyInteger('general_score');
            $table->string('general_explanation')->nullable();
            $table->tinyInteger('result_score');
            $table->string('result_explanation')->nullable();
            $table->tinyInteger('execution_score');
            $table->string('execution_explanation')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
}
