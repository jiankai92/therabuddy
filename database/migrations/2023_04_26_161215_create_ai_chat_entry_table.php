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
        Schema::create('ai_chat_entry', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('chat_id');
            $table->string('type');
            $table->string('message');
            $table->timestamp('created_at')->useCurrent();
            $table->index(['chat_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_chat_entry');
    }
};
