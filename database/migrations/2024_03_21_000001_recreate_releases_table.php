<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateReleasesTable extends Migration
{
    public function up(): void
    {
        Schema::create('releases', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('version');
            $table->timestamp('planned_at')->nullable();
            $table->timestamp('released_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('releases');
    }
}
