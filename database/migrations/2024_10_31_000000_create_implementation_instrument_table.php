<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateImplementationInstrumentTable extends Migration
{
    public function up(): void
    {
        Schema::create('implementation_instrument', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('implementation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
        });

        // Migreer de bestaande gegevens van instruments.implementation_id naar implementation_instrument zonder het Instrument model te gebruiken
        DB::table('instruments')->whereNotNull('implementation_id')->each(function ($instrument) {
            DB::table('implementation_instrument')->insert([
                'implementation_id' => $instrument->implementation_id,
                'instrument_id' => $instrument->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        // Verwijder de implementation_id kolom uit de instruments tabel
        Schema::table('instruments', function (Blueprint $table) {
            $table->dropForeign(['implementation_id']);
            $table->dropColumn('implementation_id');
        });
    }

    public function down()
    {
        // Voeg de implementation_id kolom opnieuw toe aan de instruments tabel
        Schema::table('instruments', function (Blueprint $table) {
            $table->foreignId('implementation_id')->nullable()->constrained()->cascadeOnDelete();
        });

        // Migreer de gegevens terug van implementation_instrument naar instruments
        DB::table('implementation_instrument')->each(function ($row) {
            DB::table('instruments')
                ->where('id', $row->instrument_id)
                ->update(['implementation_id' => $row->implementation_id]);
        });

        // Verwijder de tussenliggende tabel
        Schema::dropIfExists('implementation_instrument');
    }
}
