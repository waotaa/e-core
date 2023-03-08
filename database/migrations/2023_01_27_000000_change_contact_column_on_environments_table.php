<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeContactColumnOnEnvironmentsTable extends Migration
{
    public function up(): void
    {
        Schema::table('environments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('contact_id');
        });
        Schema::table('environments', function (Blueprint $table) {
            $table->foreignId('contact_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('environments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('contact_id');
        });
        Schema::table('environments', function (Blueprint $table) {
            $table->foreignId('contact_id')->nullable()->constrained();
        });
    }
}
