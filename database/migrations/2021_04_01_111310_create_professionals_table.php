<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessionalsTable extends Migration
{
    public function up(): void
    {
        Schema::create('professionals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->timestamp('last_seen_at')->nullable();

            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->boolean('email_verified')->default(false);
            $table->boolean('enabled')->default(true);
            $table->string('user_status')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('professionals');
    }
}
