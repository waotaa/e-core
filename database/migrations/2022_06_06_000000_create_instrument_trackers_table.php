<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstrumentTrackersTable extends Migration
{
    public function up(): void
    {
        Schema::create('instrument_trackers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('role');
            $table->boolean('voluntary');
            $table->dateTime('notified_at')->nullable();
            $table->string('notification_frequency')->nullable();
            $table->boolean('on_modification');
            $table->boolean('on_expiration');

            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instrument_trackers');
    }
}
