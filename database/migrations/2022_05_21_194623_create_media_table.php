<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('media', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_id')->constrained();
            $table->foreignId('user_id');
            $table->string('title');
            $table->string('public_id')->unique();
            $table->string('media_url')->unique();
            $table->text('description');
            $table->boolean('is_private')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
}
