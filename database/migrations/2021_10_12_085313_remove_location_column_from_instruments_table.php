<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveLocationColumnFromInstrumentsTable extends Migration
{
    public function up(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            $table->dropColumn('location');
        });
    }

    public function down(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            $table->string('location')->nullable();
        });
    }
}
