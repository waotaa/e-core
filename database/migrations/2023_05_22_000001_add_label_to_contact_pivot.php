<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class addLabelToContactPivot extends Migration
{
    public function up(): void
    {
        Schema::table('contactables', function (Blueprint $table) {
            $table->string('label')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('contactables', function (Blueprint $table) {
            $table->dropColumn('label');
        });
    }
}
