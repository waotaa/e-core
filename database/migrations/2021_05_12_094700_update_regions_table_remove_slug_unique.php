<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRegionsTableRemoveSlugUnique extends Migration
{
    public function up(): void
    {
        // Can't be unique due to soft deletes
        Schema::table('regions', function (Blueprint $table) {
            $table->dropUnique('regions_slug_unique');
        });
    }

    public function down(): void
    {
        Schema::table('regions', function (Blueprint $table) {
            $table->string('slug')->unique()->change();
        });
    }
}
