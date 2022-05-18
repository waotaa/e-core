<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRatingExplanationColumns extends Migration
{
    public function up(): void
    {
        Schema::table('ratings', function (Blueprint $table) {

            $table->text('general_explanation')->nullable()->change();
            $table->text('result_explanation')->nullable()->change();
            $table->text('execution_explanation')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->string('general_explanation')->nullable()->change();
            $table->string('result_explanation')->nullable()->change();
            $table->string('execution_explanation')->nullable()->change();
        });
    }
}
