<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rights', function (Blueprint $table) {
            $table->id();
            $table->string('app_name');
            $table->string('write')->nullable();
            $table->string('edit')->nullable();
            $table->string('erase')->nullable();
            $table->string('read')->nullable();
            
            $table->unsignedBigInteger('fk_userid');
            $table->foreign('fk_userid')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rights');
    }
};
