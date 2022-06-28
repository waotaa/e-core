<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeaturedOrganisationsTable extends Migration
{
    public function up(): void
    {
        Schema::create('featured_organisations', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            $table->foreignId('environment_id')->constrained()->cascadeOnDelete();
            $table->morphs('organisation');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('featured_organisations');
    }
}
