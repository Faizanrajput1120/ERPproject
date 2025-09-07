<?php

// database/migrations/xxxx_xx_xx_create_chart_of_accounts_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id(); // acc_id
            $table->string('acc_name', 100);

            // Foreign key to Level
            $table->foreignId('level_id')->constrained('levels')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chart_of_accounts');
    }
};
