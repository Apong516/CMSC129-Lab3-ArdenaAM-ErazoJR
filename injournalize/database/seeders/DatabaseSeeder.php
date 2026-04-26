<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'password' => 'password',
        ]);

        $this->call([
            JournalEntrySeeder::class,
        ]);
    }
}