<?php
namespace App\Services;

use App\Models\JournalEntry;

class JournalContextService
{
    public function resolve($message)
    {
        $msg = strtolower($message);

        if (str_contains($msg, 'how many') || str_contains($msg, 'count') || str_contains($msg, 'total')) {
            return json_encode(['total_entries' => JournalEntry::count()]);
        }

        if (str_contains($msg, 'recent') || str_contains($msg, 'latest') || str_contains($msg, 'last entry') || str_contains($msg, 'newest')) {
            return json_encode(JournalEntry::latest()->first(['id', 'title', 'content', 'created_at']));
        }

        if (str_contains($msg, 'all entries') || str_contains($msg, 'list') || str_contains($msg, 'show entries') || str_contains($msg, 'my entries')) {
            return json_encode(JournalEntry::latest()->take(10)->get(['id', 'title', 'content', 'created_at']));
        }

        if (preg_match('/entry\s+#?(\d+)/i', $message, $matches)) {
            return json_encode(JournalEntry::find($matches[1], ['id', 'title', 'content', 'created_at']));
        }

        return null;
    }
}