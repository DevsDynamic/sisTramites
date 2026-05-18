<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->json('data')->nullable();
            /*
            |--------------------------------------------------------------------------
            | EMPRESA
            |--------------------------------------------------------------------------
            */
            $table->string('business_name'); //RAZON SOCIAL
            $table->string('trade_name') // NOMBRE COMERCIAL
                ->nullable();
            $table->string('ruc', 20)->unique();
            /*
            |--------------------------------------------------------------------------
            | CONTACTO
            |--------------------------------------------------------------------------
            */
            $table->string('email');
            $table->string('phone')->nullable();
            /*
            |--------------------------------------------------------------------------
            | PLAN / SAAS
            |--------------------------------------------------------------------------
            */
            $table->foreignId('plan_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->enum('status', [
                'active',
                'suspended',
                'expired',
            ])->default('active');
            /*
            |--------------------------------------------------------------------------
            | SUSCRIPCIÓN
            |--------------------------------------------------------------------------
            */
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            /*
            |--------------------------------------------------------------------------
            | CONFIGURACIÓN FUTURA
            |--------------------------------------------------------------------------
            */
            $table->json('settings')->nullable();
            /*
            |--------------------------------------------------------------------------
            | METADATA
            |--------------------------------------------------------------------------
            */
            $table->boolean('onboarding_completed')->default(false);
            $table->string('sunat_user')->nullable();
            $table->string('sunat_password')->nullable();

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
        Schema::dropIfExists('tenants');
    }
}
