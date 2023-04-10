<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        // Using raw sql as laravel migration does not support hstore column creation
        DB::statement(
            'CREATE TABLE ai_chat
            (
            id SERIAL PRIMARY KEY,
            user_id INT NULL,
            session_id VARCHAR(255) NULL,
            metadata hstore NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_chat');
    }
};
