<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactablesTable extends Migration
{
    public function up(): void
    {
        Schema::create('contactables', function (Blueprint $table) {
            $table->foreignId('contact_id')->constrained()->cascadeOnDelete();
            $table->morphs('contactable');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contactables');
    }
}
