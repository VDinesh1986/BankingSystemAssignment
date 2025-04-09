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
        Schema::create('fund_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_account_id');
            $table->unsignedBigInteger('to_account_id');
            $table->decimal('amount', 15, 2);
            $table->decimal('converted_amount', 12, 2)->nullable(); // if currency conversion occurred
            $table->string('currency')->default('USD');
            $table->decimal('from_account_balance', 12, 2)->nullable(); // balance after transaction
            $table->decimal('to_account_balance', 12, 2)->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
            $table->foreign('from_account_id')->references('id')->on('saving_accounts')->onDelete('cascade');
            $table->foreign('to_account_id')->references('id')->on('saving_accounts')->onDelete('cascade');
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fund_transfers');
    }
};
