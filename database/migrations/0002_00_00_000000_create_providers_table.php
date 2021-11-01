<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvidersTable extends Migration
{
    public function up(): void
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

//            Used to generate uuid's: (string) Uuid::generate(4);
            $table->char('uuid', 36);
            $table->string('did')->unique()->nullable();

            $table->string('name');
            $table->boolean('is_fixed')->default(false);

            // extra info that isn't used yet?!??
            $table->string('website')->nullable();
            $table->string('email')->nullable();

            $table->string('logo_header')->nullable();
            $table->string('logo_body')->nullable();
            $table->string('logo_color')->nullable();

            $table->string('import_mark')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
}
