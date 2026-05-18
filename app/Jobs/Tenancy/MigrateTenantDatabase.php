<?php

namespace App\Jobs\Tenancy;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class MigrateTenantDatabase
{
    public function __construct(public TenantWithDatabase $tenant) {}

    public function handle(): void
    {
        $databaseName = $this->tenant->database()->getName();

        config(['database.connections.tenant.database' => $databaseName]);
        DB::purge('tenant');
        DB::reconnect('tenant');

        $connection = DB::connection('tenant');

        // Crear tabla migrations si no existe
        if (!$connection->getSchemaBuilder()->hasTable('migrations')) {
            $connection->getSchemaBuilder()->create('migrations', function ($table) {
                $table->increments('id');
                $table->string('migration');
                $table->integer('batch');
            });
        }

        // Obtener migraciones ya ejecutadas
        $ran = $connection->table('migrations')->pluck('migration')->toArray();

        // Cargar y ejecutar cada archivo manualmente
        $path = database_path('migrations/tenant');
        $files = glob($path . '/*.php');
        sort($files);

        $batch = $connection->table('migrations')->max('batch') + 1;

        foreach ($files as $file) {
            $migrationName = basename($file, '.php');

            // Saltar si ya fue ejecutada
            if (in_array($migrationName, $ran)) {
                continue;
            }

            // Cargar el archivo y obtener la instancia
            $migration = require $file;

            // Setear la conexión en la migración
            if ($migration instanceof Migration) {
                // Ejecutar en la conexión tenant
                $connection->getSchemaBuilder()->getConnection();
                
                // Usar Schema con conexión específica
                app('db')->setDefaultConnection('tenant');
                
                $migration->up();
                
                // Registrar en migrations
                $connection->table('migrations')->insert([
                    'migration' => $migrationName,
                    'batch'     => $batch,
                ]);
            }
        }

        // Restaurar conexión default
        app('db')->setDefaultConnection('mysql');

        DB::purge('tenant');
    }
}