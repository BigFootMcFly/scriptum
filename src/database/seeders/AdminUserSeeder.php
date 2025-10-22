<?php

namespace Database\Seeders;

use App\Models\User;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @throws Exception
     */
    public function run(): void
    {
        // NOTE: In docker the seeding may be re-run on each restart, so we only create the admin user if it isn't already present

        // skip, if the admin user already exists
        if (User::where('is_admin', true)->first() !== null) {
            return;
        }

        // validate admin data
        $adminName = config('scriptum.setup.admin_name');
        if ($adminName === '') {
            throw new Exception('Please provide an admin name in the .env file.');
        }

        $adminEmail = config('scriptum.setup.admin_email');
        if ($adminEmail === '') {
            throw new Exception('Please provide an admin email in the .env file.');
        }

        $adminHandle = config('scriptum.setup.admin_handle');
        if ($adminHandle === '') {
            throw new Exception('Please provide an admin handle in the .env file.');
        }

        $hashedPassword = config('scriptum.setup.admin_hashed_password');
        $plainPassword = config('scriptum.setup.admin_password');

        if ($hashedPassword == '' && $plainPassword == '') {
            throw new Exception('Please provide an admin password in the .env file.');
        }

        $adminPassword = $hashedPassword !== ''
            ? $hashedPassword
            : Hash::make($plainPassword);

        // create the admin user
        $user = User::create([
            'name' => $adminName,
            'email' => $adminEmail,
            'handle' => $adminHandle,
            'password' => $adminPassword,
            'is_admin' => true,
            'has_email_authentication' => true,
        ]);
    }
}
