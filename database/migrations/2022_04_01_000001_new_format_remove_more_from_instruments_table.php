<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewFormatRemoveMoreFromInstrumentsTable extends Migration
{
    public function up(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            $table->dropColumn('duration_unit');
        });
    }

    public function down(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            $table->string('duration_unit')->nullable();
        });
    }
}
