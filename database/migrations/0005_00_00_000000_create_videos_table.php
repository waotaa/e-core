<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();

            $table->string('provider');
            $table->string('video_identifier');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
}
