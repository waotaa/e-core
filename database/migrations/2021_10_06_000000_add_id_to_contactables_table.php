<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdToContactablesTable extends Migration
{
    public function up(): void
    {
        Schema::table('contactables', function (Blueprint $table) {
            $table->id()->first();
        });
    }

    public function down(): void
    {
        Schema::table('contactables', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }
}
