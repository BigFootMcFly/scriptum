<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // NOTE: In docker the seeding may be re-run on each restart, so we only create the admin user if it isn't already present

        // skip, if the admin user already exists
        if (User::where('is_admin', true)->first() !== null) {
            return;
        }

        $this->call([
            AdminUserSeeder::class,
        ]);
    }
}
