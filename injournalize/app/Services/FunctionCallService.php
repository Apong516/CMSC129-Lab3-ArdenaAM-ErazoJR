<?php
namespace App\Services;

use App\Models\JournalEntry;

class FunctionCallService
{
    public function execute($action, $data = null)
    {
        return match ($action) {

            'create_entry' => JournalEntry::create([
                'title' => $data['title'],
                'content' => $data['content'],
                'date' => now(),
                'location' => $data['location'] ?? null,
                'mood' => $data['mood'] ?? 'neutral',
                'user_id' => 1,
            ]),

            'update_entry' => tap(
                JournalEntry::find($data['id']),
                fn($entry) => $entry->update($data['fields'])
            ),

            'delete_entry' => JournalEntry::find($data['id'])?->delete(),

            'list_entries' => JournalEntry::latest()->take(10)->get(),

            default => null
        };
    }
}