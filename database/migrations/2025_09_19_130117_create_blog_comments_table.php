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
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('page_id'); // References pages table (blog posts)
            $table->unsignedBigInteger('user_id')->nullable(); // Registered user
            $table->string('author_name')->nullable(); // Guest commenter name
            $table->string('author_email')->nullable(); // Guest commenter email
            $table->text('content');
            $table->unsignedBigInteger('parent_id')->nullable(); // For nested comments/replies
            $table->string('status')->default('pending'); // 'pending', 'approved', 'rejected', 'spam'
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->boolean('is_guest')->default(false);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('blog_comments')->onDelete('cascade');
            $table->index(['tenant_id', 'status']);
            $table->index(['page_id', 'status']);
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_comments');
    }
};