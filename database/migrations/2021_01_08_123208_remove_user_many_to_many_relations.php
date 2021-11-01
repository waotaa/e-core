<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUserManyToManyRelations extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('region_user');
        Schema::dropIfExists('township_user');
        Schema::dropIfExists('user_user_group');
        Schema::dropIfExists('environment_user');
    }

    public function down(): void
    {
        Schema::create('region_user', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('region_id')->constrained()->cascadeOnDelete();
        });

        Schema::create('township_user', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('township_id')->constrained()->cascadeOnDelete();
        });

        Schema::create('user_user_group', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_group_id')->constrained()->cascadeOnDelete();
        });

        Schema::create('environment_user', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('environment_id')->constrained()->cascadeOnDelete();
        });
    }
}
