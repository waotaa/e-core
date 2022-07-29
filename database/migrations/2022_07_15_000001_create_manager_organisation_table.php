<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagerOrganisationTable extends Migration
{
    public function up(): void
    {
        Schema::create('manager_organisation', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('manager_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organisation_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manager_organisation');
    }
}
