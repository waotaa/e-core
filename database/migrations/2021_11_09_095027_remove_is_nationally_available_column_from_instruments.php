<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveIsNationallyAvailableColumnFromInstruments extends Migration
{
    public function up(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            $table->dropColumn('is_nationally_available');
        });
    }

    public function down(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            $table->boolean('is_nationally_available')->default(false);
        });
    }
}
