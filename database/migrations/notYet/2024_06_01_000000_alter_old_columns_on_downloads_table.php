<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterOldColumnsOnDownloadsTable extends Migration
{
    public function up(): void
    {
        // after migration of content there should not be any items without organisation_id
        // any entries that are missed are deleted
        DB::table('downloads')->whereNull('organisation_id')->delete();

        Schema::table('downloads', function (Blueprint $table) {
            $table->dropForeign(['organisation_id']);
            $table->foreignId('organisation_id')
                ->nullable(false) // remove nullable after migration
                ->change();
            $table->foreign('organisation_id')
                ->references('id')
                ->on('organisations')
                ->cascadeOnDelete();

            // remove instrument_id. Relation now in download_instrument
            $table->dropConstrainedForeignId('instrument_id');
        });
    }

    public function down(): void
    {
        Schema::table('downloads', function (Blueprint $table) {
            // restore instrument_id field
            $table->foreignId('instrument_id')
                ->nullable() // Needs to be nullable until filled again
                ->constrained()
                ->cascadeOnDelete();

            $table->dropForeign(['organisation_id']);
            $table->foreignId('organisation_id')
                ->nullable() // Restore nullable
                ->change();
            $table->foreign('organisation_id')
                ->references('id')
                ->on('organisations')
                ->cascadeOnDelete();
        });
    }
}
