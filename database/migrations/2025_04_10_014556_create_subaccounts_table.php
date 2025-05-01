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
        Schema::create('subaccounts', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('business_email');
            $table->string('account_bank');
            $table->string('account_number');
            $table->string('country');
            $table->enum('split_type', ['percentage', 'flat']);
            $table->decimal('split_value', 5, 2);
            $table->string('business_mobile');
            $table->string('flutterwave_id')->nullable();
            $table->json('flw_response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subaccounts');
    }
};
