<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsItemsTable extends Migration
{
    public function up(): void
    {
        Schema::create('news_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('environment_id')->constrained()->cascadeOnDelete();

            $table->string('title');
            $table->string('sub_title');
            $table->text('body');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_items');
    }
}
