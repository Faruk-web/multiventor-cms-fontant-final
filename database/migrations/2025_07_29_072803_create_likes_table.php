<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Create the "likes" table for the Facebook-style like system.
     */
    public function up(): void
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();

            // 🔗 Foreign key to users table
            $table->unsignedBigInteger('user_id');

            // 🔗 Foreign key to news_feeds table (not newsfeed or news_feed)
            $table->unsignedBigInteger('newsfeed_id');

            $table->timestamps();

            // ✅ Set up foreign key constraints
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->foreign('newsfeed_id')
                  ->references('id')->on('news_feeds')
                  ->onDelete('cascade');

            // 🔒 Prevent duplicate likes by same user
            $table->unique(['user_id', 'newsfeed_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
