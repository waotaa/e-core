<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodeToTilesTable extends Migration
{
    public function up(): void
    {
        Schema::table('tiles', function (Blueprint $table) {
            $table->string('code')->nullable();
//            $table->string('code')->unique();
        });
    }

    public function down(): void
    {
        Schema::table('tiles', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
}
