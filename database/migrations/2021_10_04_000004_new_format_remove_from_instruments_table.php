<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewFormatRemoveFromInstrumentsTable extends Migration
{
    public function up(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            $table->dropColumn('short_description');
            $table->dropColumn('description');
            $table->dropColumn('conditions');
            $table->dropColumn('duration');
            $table->dropColumn('costs');
            $table->dropColumn('costs_unit');
        });
    }

    public function down(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->longText('conditions')->nullable();
            $table->string('duration')->nullable();
            $table->decimal('costs')->nullable();
            $table->string('costs_unit')->nullable();
        });
    }
}
