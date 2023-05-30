<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodeToTargetGroupsTable extends Migration
{
    public function up(): void
    {
        Schema::table('target_groups', function (Blueprint $table) {
            $table->string('code')->nullable();
//            $table->string('code')->unique();
        });
    }

    public function down(): void
    {
        Schema::table('target_groups', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
}
