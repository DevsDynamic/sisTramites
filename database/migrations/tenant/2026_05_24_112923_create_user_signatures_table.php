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
        Schema::create('user_signatures', function (Blueprint $table) {

            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['digital', 'image', ]); /* TYPE */
            $table->string('pfx_path')->nullable(); /* DIGITAL SIGNATURE */
            $table->text('pfx_password')->nullable();
            $table->string('image_path')->nullable(); /* IMAGE SIGNATURE */
            $table->boolean('is_default')->default(false); /* CONFIG */
            $table->boolean('active')->default(true);
            $table->timestamp('expires_at')->nullable(); /* AUDIT */

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_signatures');
    }
};
