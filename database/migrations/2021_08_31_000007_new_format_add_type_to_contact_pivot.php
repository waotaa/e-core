<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewFormatAddTypeToContactPivot extends Migration
{
    public function up(): void
    {
        Schema::table('contactables', function (Blueprint $table) {
            $table->string('type')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('contactables', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
