<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreasTable extends Migration
{
    public function up(): void
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->nullableMorphs('area');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
}
