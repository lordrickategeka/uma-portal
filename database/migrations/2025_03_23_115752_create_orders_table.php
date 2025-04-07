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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->string('payment_tracking_id')->nullable();
            $table->string('confirmation_code')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->decimal('total_amount', 10, 2);
            $table->string('currency', 3)->default('UGX'); // Added currency
            $table->text('notes')->nullable();
            $table->timestamp('joined_at')->useCurrent();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('plan_id')->constrained();
            $table->foreignId('payment_method_id')->constrained();
            $table->string('mobile_money_number')->nullable();
            $table->string('payment_status_code')->nullable(); // Added
            $table->string('payment_account')->nullable(); // Added
            $table->string('call_back_url')->nullable(); // Added
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
