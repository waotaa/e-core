<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddFieldsToSyncAttemptsTable extends Migration
{
    public function up(): void
    {
        Schema::table('sync_attempts', function (Blueprint $table) {
            // makes resource nullable
            $table->unsignedBigInteger('resource_id')->nullable()->change();
            $table->string('resource_type')->nullable()->change();

            $table->json('results')->nullable();
            $table->text('note')->nullable();
        });
    }

    public function down(): void
    {
        DB::table('sync_attempts')
            ->whereNull('resource_id')
            ->orWhereNull('resource_type')
            ->delete();

        Schema::table('sync_attempts', function (Blueprint $table) {
            // makes resource required again
            $table->unsignedBigInteger('resource_id')->nullable(false)->change();
            $table->string('resource_type')->nullable(false)->change();

            $table->dropColumn('results');
            $table->dropColumn('note');
        });
    }
}
