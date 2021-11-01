<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateContactTypeEnumOptions extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('type')->nullable()->change();
        });
    }

    public function down(): void
    {
//        Don't return the type to an enum.
//        Doctrine, which is used in the testing, doesn't know how to handle it
//
//        Schema::table('contacts', function (Blueprint $table) {
//            $table->enum('type', ContactTypeEnum::keys())->nullable()->change();
//        });
    }
}
