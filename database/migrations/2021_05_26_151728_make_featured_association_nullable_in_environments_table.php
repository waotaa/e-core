<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeFeaturedAssociationNullableInEnvironmentsTable extends Migration
{
    public function up(): void
    {
        Schema::table('environments', function (Blueprint $table) {
            $name = 'featured_association';
            $table->string("{$name}_type")->nullable()->change();
            $table->unsignedBigInteger("{$name}_id")->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('environments', function (Blueprint $table) {
            $name = 'featured_association';
            $table->string("{$name}_type")->change();
            $table->unsignedBigInteger("{$name}_id")->change();
        });
    }
}
