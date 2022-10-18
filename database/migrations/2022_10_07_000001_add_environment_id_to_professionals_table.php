<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnvironmentIdToProfessionalsTable extends Migration
{
    public function up(): void
    {
        Schema::table('professionals', function (Blueprint $table) {
            // Needs to be nullable in able to migrate existing professionals
            $table->foreignId('environment_id')->nullable()->after('last_seen_at')->constrained()->nullOnDelete();
//            $table->foreignId('environment_id')->after('last_seen_at')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('professionals', function (Blueprint $table) {
            $table->dropConstrainedForeignId('environment_id');
        });
    }
}
