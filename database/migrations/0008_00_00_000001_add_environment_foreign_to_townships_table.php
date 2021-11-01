<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnvironmentForeignToTownshipsTable extends Migration
{
    public function up(): void
    {
        Schema::table('townships', function (Blueprint $table) {
            $table->foreignId('environment_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('townships', function (Blueprint $table) {
            $table->dropConstrainedForeignId('environment_id');
        });
    }
}
