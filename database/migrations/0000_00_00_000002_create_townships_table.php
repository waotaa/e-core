<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTownshipsTable extends Migration
{
    public function up(): void
    {
        Schema::create('townships', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('region_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->string('code');
            $table->string('featureId')->nullable();
            $table->string('slug');
            $table->longText('description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('townships');
    }
}
