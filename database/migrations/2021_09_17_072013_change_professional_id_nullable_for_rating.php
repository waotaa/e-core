<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeProfessionalIdNullableForRating extends Migration
{
    public function up(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropForeign(['professional_id']);
            $table->foreignId('professional_id')->nullable()->change()
                ->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropForeign(['professional_id']);
            $table->foreignId('professional_id')->change()
                ->constrained()->cascadeOnDelete();
        });
    }
}
