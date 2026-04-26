<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JournalEntry;

class JournalEntrySeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\JournalEntry::factory()->count(15)->create();
    }
}