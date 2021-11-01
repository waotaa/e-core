<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublicationDatesToNewsItemsTable extends Migration
{
    public function up(): void
    {
        Schema::table('news_items', function (Blueprint $table) {
            $table->date('publish_from')->nullable();
            $table->date('publish_to')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('news_items', function (Blueprint $table) {
            $table->dropColumn('publish_from');
            $table->dropColumn('publish_to');
        });
    }
}
