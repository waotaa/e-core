<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveDidColumns extends Migration
{
    public function up(): void
    {
        Schema::table('instruments', function(Blueprint $table) {
            $table->dropColumn('did');
        });

        Schema::table('providers', function(Blueprint $table) {
            $table->dropColumn('did');
        });
    }

    public function down(): void
    {
        Schema::table('instruments', function(Blueprint $table) {
            $table->string('did')->unique()->nullable();
        });

        Schema::table('providers', function(Blueprint $table) {
            $table->string('did')->unique()->nullable();
        });
    }
}
