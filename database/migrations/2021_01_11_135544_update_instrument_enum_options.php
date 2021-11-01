<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInstrumentEnumOptions extends Migration
{
    public function up(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            $table->string('duration_unit')->nullable()->change();
            $table->string('costs_unit')->nullable()->change();
            $table->string('location')->nullable()->change();
        });
    }

    public function down(): void
    {
//        Don't return the type to an enum.
//        Doctrine, which is used in the testing, doesn't know how to handle it
//
//        Schema::table('instruments', function (Blueprint $table) {
//            $table->enum('duration_unit', DurationUnitEnum::keys())->nullable()->change();
//            $table->enum('costs_unit', CostsUnitEnum::keys())->nullable()->change();
//            $table->enum('location', LocationEnum::keys())->nullable()->change();
//        });
    }
}
