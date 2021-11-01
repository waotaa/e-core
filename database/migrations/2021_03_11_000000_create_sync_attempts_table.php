<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyncAttemptsTable extends Migration
{
    public function up(): void
    {
        Schema::create('sync_attempts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->morphs('resource');
            $table->nullableMorphs('origin');

            $table->string('action')->nullable();
            $table->string('status')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sync_attempts');
    }
}
