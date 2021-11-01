<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFilenameColumnToDownloadsTable extends Migration
{
    public function up(): void
    {
        Schema::table('downloads', function (Blueprint $table) {
            $table->string('filename')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('downloads', function (Blueprint $table) {
            $table->dropColumn('filename');
        });
    }
}
