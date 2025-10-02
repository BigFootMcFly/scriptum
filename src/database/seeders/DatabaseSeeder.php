<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        //NOTE: In docker the seeding may be re-run on each restart, so we only create the admin user if it isn't already present

        // skip, if the admin user already exists
        if (null !== User::where('is_admin', true)->first()) {
            return;
        }

        // create the admin user
        $user = User::create([
            'name' => config('scriptum.setup.admin_name'),
            'email' => config('scriptum.setup.admin_email'),
            'handle' => config('scriptum.setup.admin_handle'),
            'password' => Hash::make(config('scriptum.setup.admin_password')),
            'is_admin' => true,
        ]);

    }
}
