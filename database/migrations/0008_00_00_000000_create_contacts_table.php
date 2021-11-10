<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->morphs('contactable');

            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('type')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
}
