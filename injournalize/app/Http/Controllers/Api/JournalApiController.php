<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JournalEntry;

class JournalApiController extends Controller
{
    public function recent()
    {
        return response()->json(
            JournalEntry::latest()->first(['id', 'title', 'content', 'created_at'])
        );
    }

    public function all()
    {
        return response()->json(
            JournalEntry::latest()->take(10)->get(['id', 'title', 'content', 'created_at'])
        );
    }

    public function count()
    {
        return response()->json(['total_entries' => JournalEntry::count()]);
    }

    public function find($id)
    {
        return response()->json(
            JournalEntry::findOrFail($id, ['id', 'title', 'content', 'created_at'])
        );
    }
}