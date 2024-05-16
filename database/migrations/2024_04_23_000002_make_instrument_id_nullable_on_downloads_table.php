<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeInstrumentIdNullableOnDownloadsTable extends Migration
{
    public function up(): void
    {
        Schema::table('downloads', function (Blueprint $table) {
            $table->dropForeign(['instrument_id']);
            $table->foreignId('instrument_id')
                ->nullable()
                ->change();
            $table->foreign('instrument_id')
                ->references('id')
                ->on('instruments')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('downloads', function (Blueprint $table) {
            $table->dropForeign(['instrument_id']);
            $table->foreignId('instrument_id')
                ->nullable(false)
                ->change();
            $table->foreign('instrument_id')
                ->references('id')
                ->on('instruments')
                ->cascadeOnDelete();
        });
    }
}
