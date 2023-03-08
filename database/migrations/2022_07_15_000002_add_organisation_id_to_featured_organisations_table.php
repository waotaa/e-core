<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganisationIdToFeaturedOrganisationsTable extends Migration
{
    public function up(): void
    {
        // rename current featured_organisations to featured_associations (also rename the foreign key)
        Schema::table('featured_organisations', function (Blueprint $table) {
            $table->dropForeign('featured_organisations_environment_id_foreign');
            $table->foreign('environment_id', 'featured_associations_environment_id_foreign')
                ->references('id')
                ->on('environments')
                ->onDelete('cascade');
        });
        Schema::rename('featured_organisations', 'featured_associations');
        // The featured_associations may be removed when all parties migrated to the open source solution

        // create the wanted featured organisations
        Schema::create('featured_organisations', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            $table->foreignId('environment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organisation_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('featured_organisations');
        Schema::rename('featured_associations', 'featured_organisations');
        Schema::table('featured_organisations', function (Blueprint $table) {
            $table->dropForeign('featured_associations_environment_id_foreign');
            $table->foreign('environment_id', 'featured_organisations_environment_id_foreign')
                ->references('id')
                ->on('environments')
                ->onDelete('cascade');
        });
    }
}
