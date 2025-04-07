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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');  // Title of the blog post
            $table->string('slug')->unique();  // Slug for SEO-friendly URL
            $table->text('content');  // Content of the blog post
            $table->string('image')->nullable();  // Image associated with the blog (optional)
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');  // Foreign key to users table (author)
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');  // Foreign key to branches table (nullable)
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');  // Status of the blog
            $table->timestamp('published_at')->nullable();  // Timestamp for when the blog is published
            $table->integer('views')->default(0);  // View counter for the blog
            $table->string('post_type')->default('post');
            $table->timestamps();  // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
