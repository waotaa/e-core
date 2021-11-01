<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewFormatAddToInstrumentsTable extends Migration
{
    public function up(): void
    {
        Schema::table('instruments', function (Blueprint $table) {

            // samenvatting
            // was: short_description
            $table->text('summary');

            // uitvoeringsvorm
            $table->foreignId('implementation_id')->nullable()->constrained()->nullOnDelete();

            // werkwijze
            $table->string('method')->nullable();

            // group/individueel
            $table->foreignId('group_form_id')->nullable()->constrained()->nullOnDelete();

            // toelichting doelgroep
            $table->longText('target_group_description')->nullable();

            // voorwaarde deelname
            $table->longText('participation_conditions')->nullable();

            // aanvullende informatie
            $table->longText('additional_information')->nullable();

            // locaties uitvoering, moet belongs to worden

            // toelichting locatie
            $table->longText('location_description')->nullable()->change();

            // werkafspraken
            $table->longText('work_agreements')->nullable();

            // registratiecodes, one to many worden

            // intensiteit
            $table->integer('intensity_hours_per_week')->nullable();

            // totale duur waarde
            $table->integer('total_duration_value')->nullable();

            // totale duur eenheid
            $table->string('total_duration_unit')->nullable();

            // totale kosten
            // Geen getal + eenheid meer
            $table->string('total_costs')->nullable();

            // toelichting intensiteit, duur, kosten
            $table->longText('intensity_duration_costs_description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            $table->dropColumn('summary');
            $table->dropConstrainedForeignId('implementation_id');
            $table->dropColumn('method');
            $table->dropConstrainedForeignId('group_form_id');
            $table->dropColumn('target_group_description');
            $table->dropColumn('participation_conditions');
            $table->dropColumn('additional_information');
            $table->string('location_description')->nullable()->change();
            $table->dropColumn('work_agreements');
            $table->dropColumn('intensity_hours_per_week');
            $table->dropColumn('total_duration_value');
            $table->dropColumn('total_duration_unit');
            $table->dropColumn('total_costs');
            $table->dropColumn('intensity_duration_costs_description');
        });
    }
}
