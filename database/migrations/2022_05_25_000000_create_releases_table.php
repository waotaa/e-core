<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReleasesTable extends Migration
{
    public function up(): void
    {
        Schema::create('releases', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('version');
            $table->json('tasks')->nullable();
            $table->string('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('releases');
    }
}
