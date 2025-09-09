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
        Schema::create('trn_dtl', function (Blueprint $table) {
            $table->id();
            $table->string('v_no');
            $table->string('v_type');
            $table->unsignedBigInteger('aid');
            $table->unsignedBigInteger('cid');
            $table->text('descr')->nullable();
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->string('status')->default('active');
            $table->unsignedBigInteger('prepared_by');
            $table->string('cash_acc');
            $table->decimal('pre_bal', 15, 2)->default(0);
            $table->timestamps();

            // Correct foreign key constraints
            $table->foreign('aid')->references('acc_id')->on('chart_of_accounts');
            $table->foreign('cid')->references('cid')->on('workspace');
            $table->foreign('prepared_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trn_dtl');
    }
};