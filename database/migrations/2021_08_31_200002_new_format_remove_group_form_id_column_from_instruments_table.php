<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewFormatRemoveGroupFormIdColumnFromInstrumentsTable extends Migration
{
    public function up(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('group_form_id');
        });
    }

    public function down(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            $table->foreignId('group_form_id')->nullable()->constrained()->nullOnDelete();
        });
    }
}
