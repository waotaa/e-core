<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveThemesTable extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('themes');
    }

    public function down(): void
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->string('description');
        });
    }
}
