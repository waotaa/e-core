<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePostbusnummerAndAntwoordnummerToStringInAddressesTable extends Migration
{
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('postbusnummer')->nullable()->change();
            $table->string('antwoordnummer')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->integer('postbusnummer')->nullable()->change();
            $table->integer('antwoordnummer')->nullable()->change();
        });
    }
}

