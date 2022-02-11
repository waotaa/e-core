<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCrisisColumnToTilesTable extends Migration
{
    public function up(): void
    {
        Schema::table('tiles', function (Blueprint $table) {
            $table->text('excerpt');
            $table->text('crisis_description');
            $table->text('crisis_services');
        });
    }

    public function down(): void
    {
        Schema::table('tiles', function (Blueprint $table) {
            $table->dropColumn('excerpt');
            $table->dropColumn('crisis_description');
            $table->dropColumn('crisis_services');
        });
    }
}
