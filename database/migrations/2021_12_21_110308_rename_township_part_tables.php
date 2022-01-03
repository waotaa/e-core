<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTownshipPartTables extends Migration
{
    public function up(): void
    {
        Schema::table('available_township_part_instrument', function (Blueprint $table) {
            $table->dropForeign(['township_part_id']);
            $table->renameColumn('township_part_id', 'neighbourhood_id');
        });

        Schema::rename('township_parts', 'neighbourhoods');
        Schema::rename('available_township_part_instrument', 'available_neighbourhood_instrument');

        Schema::table('available_neighbourhood_instrument', function (Blueprint $table) {
            $table->renameIndex(
                'available_township_part_instrument_instrument_id_foreign',
                'available_neighbourhood_instrument_instrument_id_foreign'
            );
            $table->foreign('neighbourhood_id')->references('id')->on('neighbourhoods')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('available_neighbourhood_instrument', function (Blueprint $table) {
            $table->dropForeign(['neighbourhood_id']);
            $table->renameColumn('neighbourhood_id', 'township_part_id');
        });

        Schema::rename('neighbourhoods', 'township_parts');
        Schema::rename('available_neighbourhood_instrument', 'available_township_part_instrument');

        Schema::table('available_township_part_instrument', function (Blueprint $table) {
            $table->renameIndex(
                'available_neighbourhood_instrument_instrument_id_foreign',
                'available_township_part_instrument_instrument_id_foreign'
            );
            $table->foreign('township_part_id')->references('id')->on('township_parts')->onDelete('cascade');
        });
    }
}
