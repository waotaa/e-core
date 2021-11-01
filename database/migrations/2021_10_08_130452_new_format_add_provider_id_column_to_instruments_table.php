<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewFormatAddProviderIdColumnToInstrumentsTable extends Migration
{
    public function up(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            $table->foreignId('provider_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('provider_id');
        });
    }
}
