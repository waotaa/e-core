<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class RenameReleasesTable extends Migration
{
    public function up(): void
    {
        Schema::rename('releases', 'updates');
    }

    public function down(): void
    {
        Schema::rename('updates', 'releases');
    }
}
