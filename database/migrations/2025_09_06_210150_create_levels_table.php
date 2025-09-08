<?php

// database/migrations/xxxx_xx_xx_create_levels_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->id('level_id'); // level_id
            $table->string('level_title')->default('No Title');

            // Foreign keys
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('cascade');
            $table->unsignedBigInteger('pre_id')->nullable();
            $table->foreign('pre_id')->references('level_id')->on('levels');

            

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};
