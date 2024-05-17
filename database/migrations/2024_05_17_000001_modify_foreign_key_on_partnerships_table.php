<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyForeignKeyOnPartnershipsTable extends Migration
{
    public function up()
    {
        Schema::table('partnerships', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['organisation_id']);

            // Add the new foreign key constraint with cascadeOnDelete
            $table->foreign('organisation_id')
                ->references('id')
                ->on('organisations')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('partnerships', function (Blueprint $table) {
            // Drop the foreign key constraint with cascadeOnDelete
            $table->dropForeign(['organisation_id']);

            // Restore the original foreign key constraint with nullOnDelete
            $table->foreign('organisation_id')
                ->references('id')
                ->on('organisations')
                ->onDelete('set null');
        });
    }
}
