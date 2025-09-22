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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('language_id')->constrained()->onDelete('cascade');
            $table->string('group'); // frontend, backend, emails, etc.
            $table->string('key'); // translation key
            $table->text('value'); // translated text
            $table->boolean('is_auto_translated')->default(false);
            $table->boolean('needs_review')->default(false);
            $table->timestamps();
            
            $table->unique(['tenant_id', 'language_id', 'group', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
