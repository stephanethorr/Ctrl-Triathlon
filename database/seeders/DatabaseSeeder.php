<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Création des rôles (ID 1 = Infirmier, ID 2 = Laboratoire)
        DB::table('roles')->updateOrInsert(
            ['id' => 1],
            ['roles_name' => 'Infirmier', 'created_at' => now()]
        );

        DB::table('roles')->updateOrInsert(
            ['id' => 2],
            ['roles_name' => 'Laboratoire', 'created_at' => now()]
        );

        // 2. Création du compte Infirmier (a.a@gmail.com)
        User::updateOrCreate(
            ['email' => 'a.a@gmail.com'], // La condition de recherche (pour éviter les doublons)
            [
                'name'              => 'Compte Infirmier',
                'password'          => Hash::make('123456789'), // Hachage obligatoire du mot de passe
                'role_id'           => 1, // Lié au rôle Infirmier
                'email_verified_at' => now(),
            ]
        );

        // 3. Création du compte Laboratoire (b.b@gmail.com)
        User::updateOrCreate(
            ['email' => 'b.b@gmail.com'],
            [
                'name'              => 'Compte Laboratoire',
                'password'          => Hash::make('123456789'),
                'role_id'           => 2, // Lié au rôle Laboratoire
                'email_verified_at' => now(),
            ]
        );
    }
}