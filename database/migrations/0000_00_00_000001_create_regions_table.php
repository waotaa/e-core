<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionsTable extends Migration
{
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('color')->nullable();

            $table->longText('description')->nullable();
            $table->longText('cooperation_partners')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
}
