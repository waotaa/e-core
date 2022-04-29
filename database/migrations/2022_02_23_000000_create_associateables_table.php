<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssociateablesTable extends Migration
{
    public function up(): void
    {
        Schema::create('associateables', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('associateable');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('associateables');
    }
}
