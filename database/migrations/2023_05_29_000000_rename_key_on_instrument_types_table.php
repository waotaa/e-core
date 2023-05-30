<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameKeyOnInstrumentTypesTable extends Migration
{
    public function up(): void
    {
        Schema::table('instrument_types', function (Blueprint $table) {
            $table->renameColumn('key', 'code');
        });
    }

    public function down(): void
    {
        Schema::table('instrument_types', function (Blueprint $table) {
            $table->renameColumn('code', 'key');
        });
    }
}
