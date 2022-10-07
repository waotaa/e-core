<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandExecutionsTable extends Migration
{
    public function up(): void
    {
        Schema::create('command_executions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('command');
            $table->string('status');
            $table->string('note')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('command_executions');
    }
}
