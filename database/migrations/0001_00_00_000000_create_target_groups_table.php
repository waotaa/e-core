<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTargetGroupsTable extends Migration
{
    public function up(): void
    {
        Schema::create('target_groups', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->string('description');
            $table->boolean('custom')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('target_groups');
    }
}
