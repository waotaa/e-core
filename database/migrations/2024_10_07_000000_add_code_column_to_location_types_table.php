<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodeColumnToLocationTypesTable extends Migration
{
    public function up(): void
    {
        Schema::table('location_types', function (Blueprint $table) {
            $table->string('code');
        });
    }

    public function down(): void
    {
        Schema::table('location_types', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
}
