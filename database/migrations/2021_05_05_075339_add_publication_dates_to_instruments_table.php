<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublicationDatesToInstrumentsTable extends Migration
{
    public function up(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            $table->date('publish_from')->nullable();
            $table->date('publish_to')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            $table->dropColumn('publish_from');
            $table->dropColumn('publish_to');
        });
    }
}
