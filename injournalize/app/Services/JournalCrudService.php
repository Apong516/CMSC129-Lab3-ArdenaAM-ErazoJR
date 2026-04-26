<?php
namespace App\Services;

use App\Models\JournalEntry;

class JournalCrudService
{
    public function create($title, $content)
    {
        $entry = JournalEntry::create([
            'title' => $title,
            'content' => $content,
        ]);

        return $entry;
    }

    public function update($id, $data)
    {
        $entry = JournalEntry::findOrFail($id);

        if (isset($data['title'])) $entry->title = $data['title'];
        if (isset($data['content'])) $entry->content = $data['content'];

        $entry->save();

        return $entry;
    }

    public function delete($id)
    {
        $entry = JournalEntry::findOrFail($id);
        $title = $entry->title;
        $entry->delete();

        return $title;
    }
}