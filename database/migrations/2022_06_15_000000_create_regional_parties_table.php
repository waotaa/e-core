<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionalPartiesTable extends Migration
{
    public function up(): void
    {
        Schema::create('regional_parties', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('region_id')->constrained()->cascadeOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regional_parties');
    }
}
