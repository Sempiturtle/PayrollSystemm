<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ModeratorSeeder extends Seeder
{
    /**
     * Seed one moderator account.
     *
     * Credentials:
     *   Email    : moderator@aisat.edu.ph
     *   Password : AISAT-MOD-001
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'moderator@aisat.edu.ph'],
            [
                'name'        => 'AISAT Moderator',
                'password'    => Hash::make('AISAT-MOD-001'),
                'role'        => 'moderator',
                'employee_id' => 'MOD-001',
            ]
        );

        $this->command->info('Moderator seeded → moderator@aisat.edu.ph / AISAT-MOD-001');
    }
}
