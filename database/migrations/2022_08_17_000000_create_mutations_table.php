<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMutationsTable extends Migration
{
    public function up(): void
    {
        Schema::create('mutations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->char('batch_id', 36);
            $table->foreignId('manager_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->string('loggable_type');
            $table->unsignedBigInteger('loggable_id');
            $table->string('target_type');
            $table->unsignedBigInteger('target_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id')->nullable();

            $table->text('fields');
            $table->string('status', 25)->default('running');
            $table->text('exception');

            $table->index(['loggable_type', 'loggable_id']);
            $table->index(['batch_id', 'model_type', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutations');
    }
}
