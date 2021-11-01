<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRegionCodeColumnToTownshipsTable extends Migration
{
    public function up()
    {
        Schema::table('townships', function (Blueprint $table) {
            $table->string('region_code');
        });
    }

    public function down(): void
    {
        Schema::table('townships', function (Blueprint $table) {
            $table->dropColumn('region_code');
        });
    }
}
