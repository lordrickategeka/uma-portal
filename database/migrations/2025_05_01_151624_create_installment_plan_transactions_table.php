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
        Schema::create('installment_plan_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installment_plan_id')->constrained()->onDelete('cascade');
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->integer('installment_number');
            $table->decimal('applied_amount', 10, 2); // Amount from this transaction applied to the installment
            $table->timestamps();
            
            // Ensure each transaction is only linked once to an installment plan
            $table->unique(['installment_plan_id', 'transaction_id'], 'installment_plan_transaction_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installment_plan_transactions');
    }
};
