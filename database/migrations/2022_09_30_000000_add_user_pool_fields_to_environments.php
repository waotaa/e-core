<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserPoolFieldsToEnvironments extends Migration
{
    public function up(): void
    {
        Schema::table('environments', function (Blueprint $table) {
            $table->string('user_pool_id')->nullable();
            $table->string('user_pool_client_id')->nullable();
            $table->string('url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('environments', function (Blueprint $table) {
            $table->dropColumn('user_pool_id');
            $table->dropColumn('user_pool_client_id');
            $table->dropColumn('url');
        });
    }
}
