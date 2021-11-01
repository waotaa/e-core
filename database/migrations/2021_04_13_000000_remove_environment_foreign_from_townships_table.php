<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class RemoveEnvironmentForeignFromTownshipsTable
 *
 * Reversal of 0008_00_00_000001
 */
class RemoveEnvironmentForeignFromTownshipsTable extends Migration
{
    public function up(): void
    {
        Schema::table('townships', function (Blueprint $table) {
            $table->dropConstrainedForeignId('environment_id');
        });
    }

    public function down(): void
    {
        Schema::table('townships', function (Blueprint $table) {
            $table->foreignId('environment_id')->nullable()->constrained()->nullOnDelete();
        });
    }
}
