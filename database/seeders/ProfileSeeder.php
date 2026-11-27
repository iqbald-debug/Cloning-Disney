<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'demo@disneyclone.test'],
            [
                'name' => 'Disney Demo',
                'password' => 'password123',
            ]
        );

        $profiles = [
            [
                'name' => 'Mahasiswa',
                'avatar_url' => 'https://api.dicebear.com/9.x/adventurer/svg?seed=Mahasiswa',
                'is_kids' => false,
            ],
            [
                'name' => 'Kids',
                'avatar_url' => 'https://api.dicebear.com/9.x/fun-emoji/svg?seed=Kids',
                'is_kids' => true,
            ],
            [
                'name' => 'Guest',
                'avatar_url' => 'https://api.dicebear.com/9.x/adventurer/svg?seed=Guest',
                'is_kids' => false,
            ],
        ];

        foreach ($profiles as $profile) {
            $user->profiles()->updateOrCreate(
                ['name' => $profile['name']],
                $profile
            );
        }
    }
}
