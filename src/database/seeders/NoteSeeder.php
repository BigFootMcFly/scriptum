<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->count(rand(5, 10))
            ->addNotes()
            ->create();
    }
}
