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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Branch name
            $table->string('code')->unique(); // Unique identifier e.g. BR001
            $table->string('location'); // City or area
            $table->string('email')->nullable();
            $table->string('address')->nullable(); // Full address
            $table->string('phone_number')->nullable();
            $table->string('manager_name')->nullable(); // Name of the branch manager
            $table->enum('status', ['active', 'inactive'])->default('active'); // Operational status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
