<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateContactables extends Migration
{
    public function up(): void
    {
        Schema::table('contactables', function (Blueprint $table) {
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('contactables', function (Blueprint $table) {
            $table->removeColumn('created_at');
            $table->removeColumn('updated_at');
        });
    }
}
