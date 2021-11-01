<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnvironmentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('environments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->string('name');
            $table->string('slug')->unique();

            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('color_primary')->nullable();
            $table->string('color_secondary')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('environments');
    }
}
