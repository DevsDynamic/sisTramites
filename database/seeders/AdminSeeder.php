<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env(
            'SYSTEM_OWNER_EMAIL',
            'admin@syvtechnology.com'
        );

        $user = User::where('email', $email)->first();

        if (! $user) {
            $password = env('SYSTEM_OWNER_PASSWORD');

            if (! $password) {
                throw new \RuntimeException(
                    'Debe configurar SYSTEM_OWNER_PASSWORD en una instalación nueva.'
                );
            }

            $user = User::create([
                'name' => 'Admin',
                'email' => $email,
                'password' => bcrypt($password),
            ]);
        }

        $user->update(['is_system_owner' => true]);

        $user->assignRole('Administrador');
    }
}
