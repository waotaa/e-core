<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTilesTable extends Migration
{
    public function up(): void
    {
        Schema::create('tiles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->string('name');
            $table->string('sub_title');
            $table->text('description');
            $table->text('list');
//            $table->enum('key', TileEnum::keys());
            $table->string('key')->nullable();
            $table->json('position');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tiles');
    }
}
