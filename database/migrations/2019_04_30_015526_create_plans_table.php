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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            /*
            |--------------------------------------------------------------------------
            | INFORMACIÓN
            |--------------------------------------------------------------------------
            */
            $table->string('name');
            $table->text('description')
                ->nullable();
            /*
            |--------------------------------------------------------------------------
            | COSTO
            |--------------------------------------------------------------------------
            */
            $table->decimal('price', 10, 2)
                ->default(0);

            $table->integer('duration_days')
                ->default(30);
            /*
            |--------------------------------------------------------------------------
            | LÍMITES SAAS
            |--------------------------------------------------------------------------
            */
            $table->integer('max_users')
                ->default(1);

            $table->integer('max_signatures')
                ->default(0);

            $table->integer('max_documents')
                ->default(0);
            /*
            |--------------------------------------------------------------------------
            | ESTADO
            |--------------------------------------------------------------------------
            */
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
