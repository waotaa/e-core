<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SplitIntensityDurationCostsDescriptionColumnForInstrumentsTable extends Migration
{
    public function up(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            $table->renameColumn('intensity_duration_costs_description', 'duration_description');
            $table->longText('costs_description')->nullable();
            $table->longText('intensity_description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            $table->renameColumn('duration_description', 'intensity_duration_costs_description');
            $table->dropColumn('costs_description');
            $table->dropColumn('intensity_description');
        });
    }
}
