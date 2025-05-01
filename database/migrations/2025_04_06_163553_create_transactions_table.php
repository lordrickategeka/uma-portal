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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('email');
            $table->string('name');
            $table->string('phone');
            $table->string('status')->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('payment_link')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->nullable()->constrained()->onDelete('cascade');
            $table->json('payment_data')->nullable();
            $table->string('purpose')->nullable(); // 'installment', 'full_payment', etc.
            $table->foreignId('installment_plan_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('installment_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
