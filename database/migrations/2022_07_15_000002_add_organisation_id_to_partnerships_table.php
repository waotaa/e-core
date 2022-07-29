<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganisationIdToPartnershipsTable extends Migration
{
    public function up(): void
    {
        Schema::table('partnerships', function (Blueprint $table) {
            $table->foreignId('organisation_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('partnerships', function (Blueprint $table) {
            $table->dropConstrainedForeignId('organisation_id');
        });
    }
}
