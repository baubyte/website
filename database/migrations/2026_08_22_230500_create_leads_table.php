<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * PR11 — chat proxy (D8: `ChatController` proxies Laravel -> n8n
     * webhook, browser never talks to n8n directly). This table is the
     * lead-capture record replacing the old contact form: one row per
     * chat message received, whether or not n8n answered successfully.
     *
     * `client_hash` is the ONLY thing derived from the visitor's IP/User-
     * Agent that is ever persisted (an HMAC digest, see `ChatController`)
     * -- the raw IP/UA must never reach the database.
     */
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->uuid('conversation_id')->index();
            $table->text('message');
            $table->string('locale', 5);
            $table->string('page')->nullable();
            $table->string('client_hash', 64);
            $table->string('reply_status', 20);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
