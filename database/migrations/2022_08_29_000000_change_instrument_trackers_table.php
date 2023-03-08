<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeInstrumentTrackersTable extends Migration
{
    public function up(): void
    {
        DB::table('instrument_trackers')->truncate();

        Schema::table('instrument_trackers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->foreignId('manager_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('instrument_trackers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('manager_id');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        });
    }
}
