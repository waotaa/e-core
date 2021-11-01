<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewFormatCreateClientCharTable extends Migration
{
    public function up(): void
    {
        Schema::create('client_characteristics', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_characteristics');
    }
}
