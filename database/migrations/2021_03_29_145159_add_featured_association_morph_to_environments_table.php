<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeaturedAssociationMorphToEnvironmentsTable extends Migration
{
    public function up(): void
    {
        Schema::table('environments', function (Blueprint $table) {
            $table->nullableMorphs('featured_association', 'environments_feat_association_type_feat_association_id_index');
        });
    }

    public function down(): void
    {
        Schema::table('environments', function (Blueprint $table) {
            $table->dropMorphs('featured_association', 'environments_feat_association_type_feat_association_id_index');
        });
    }
}
