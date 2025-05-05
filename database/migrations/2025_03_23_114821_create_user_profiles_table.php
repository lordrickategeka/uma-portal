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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('uma_number')->nullable()->unique();
            $table->string('gender');
            $table->string('marital_status');
            $table->integer('age');
            $table->string('phone');
            $table->text('address');
            $table->string('next_of_kin')->nullable();
            $table->string('next_of_kin_phone')->nullable();
            $table->string('uma_branch')->nullable();
            $table->string('employer')->nullable();
            $table->string('category');
            $table->string('specialization')->nullable();
            $table->string('umdpc_number')->nullable();
            $table->foreignId('membership_category_id')->constrained('membership_categories')->onDelete('cascade');
            $table->string('referee');
            $table->string('referee_phone1');
            $table->string('referee_phone2')->nullable();
            $table->string('photo')->nullable();
            $table->string('signature')->nullable();
            $table->string('national_id');
            $table->string('license_document')->nullable();
            $table->string('registration_status')->default('Completed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
