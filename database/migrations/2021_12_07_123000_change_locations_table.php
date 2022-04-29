<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLocationsTable extends Migration
{
    public function up(): void
    {
        Schema::rename('locations', 'location_types');
        Schema::rename('instrument_location', 'instrument_location_type');
        Schema::table('instrument_location_type', function (Blueprint $table) {
            $table->dropForeign('instrument_location_location_id_foreign');
            $table->renameColumn('location_id', 'location_type_id');
            $table->foreign('location_type_id')->references('id')->on('location_types')->onDelete('cascade');
        });

        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->boolean('is_active')->default(true);
            $table->longText('description')->nullable();
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
            $table->foreignId('address_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');

        Schema::rename('location_types', 'locations');
        Schema::rename('instrument_location_type', 'instrument_location');
        Schema::table('instrument_location', function (Blueprint $table) {
            $table->dropForeign('instrument_location_type_location_type_id_foreign');
            $table->renameColumn('location_type_id', 'location_id');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
        });
    }

}
