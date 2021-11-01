<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnershipTownshipTable extends Migration
{
    public function up(): void
    {
        Schema::create('partnership_township', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('partnership_id')->constrained()->cascadeOnDelete();
            $table->foreignId('township_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partnership_township');
    }
}
