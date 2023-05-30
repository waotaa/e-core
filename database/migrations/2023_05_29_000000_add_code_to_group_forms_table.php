<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodeToGroupFormsTable extends Migration
{
    public function up(): void
    {
        Schema::table('group_forms', function (Blueprint $table) {
            $table->string('code')->nullable();
//            $table->string('code')->unique();
        });
    }

    public function down(): void
    {
        Schema::table('group_forms', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
}
