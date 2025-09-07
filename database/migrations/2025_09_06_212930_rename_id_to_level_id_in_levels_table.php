<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('levels', function (Blueprint $table) {
            // Drop foreign keys that reference the old id (pre_id if exists)
            $table->dropForeign(['pre_id']); 

            // Rename primary key column
            $table->renameColumn('id', 'level_id');

            // Re-add foreign key for pre_id referencing new level_id
            $table->foreign('pre_id')->references('level_id')->on('levels')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('levels', function (Blueprint $table) {
            $table->dropForeign(['pre_id']);
            $table->renameColumn('level_id', 'id');
            $table->foreign('pre_id')->references('id')->on('levels')->onDelete('cascade');
        });
    }
};
