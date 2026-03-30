<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Models\User;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        $activeUserId = session('active_user');
        $activeUser = User::find($activeUserId); // get the active user

        if (! User::count()) {
            return redirect()->route('users.create')->with('info', 'Please create a profile first.');
        }

        // Only redirect to create if the route is not already 'users.create'
        if (! User::count() && request()->route()->getName() != 'users.create') {
            return redirect()->route('users.create')->with('info', 'Please create a profile first.');
        }

        if (! $activeUserId) {
            return redirect()->route('users.index')->with('info', 'Please select a profile.');
        }

        $search = $request->input('search');
        $mood = $request->input('mood');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $journals = JournalEntry::where('user_id', $activeUserId)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%")
                        ->orWhere('mood', 'like', "%{$search}%");
                });
            })
            ->when($mood, function ($query, $mood) {
                $query->where('mood', $mood);
            })
            ->when($fromDate, function ($query, $fromDate) {
                $query->whereDate('date', '>=', $fromDate);
            })
            ->when($toDate, function ($query, $toDate) {
                $query->whereDate('date', '<=', $toDate);
            })
            ->orderBy('date', 'desc')
            ->get();

        $trashedJournals = JournalEntry::onlyTrashed()
            ->where('user_id', $activeUserId)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%")
                        ->orWhere('mood', 'like', "%{$search}%");
                });
            })
            ->orderBy('deleted_at', 'desc')
            ->get();

        return view('journals.index', compact('journals', 'trashedJournals', 'search', 'mood', 'activeUser'));
    }

    public function create()
    {
        $users = User::all();

        return view('journals.create', compact('users'));
    }

    public function store(Request $request)
    {
        $activeUserId = session('active_user'); // active user from session

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'date' => 'required|date',
            'mood' => 'required|string',
            'location' => 'required|string|max:255',
        ]);

        JournalEntry::create([
            'user_id' => $activeUserId,
            'title' => $request->title,
            'content' => $request->content,
            'date' => $request->date,
            'mood' => $request->mood,
            'location' => $request->location,
        ]);

        return redirect()->route('journals.index')->with('success', 'Journal added!');
    }

    public function edit(JournalEntry $journal)
    {
        $users = User::all();

        return view('journals.edit', compact('journal', 'users'));
    }

    public function update(Request $request, JournalEntry $journal)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'date' => 'required|date',
            'mood' => 'required|string',
            'location' => 'reuqired|string|max:255',
        ]);

        $journal->update($request->all());

        return redirect()->route('journals.index')->with('success', 'Journal updated!');
    }

    public function destroy(JournalEntry $journal)
    {
        $journal->delete();

        return redirect()->route('journals.index')->with('success', 'Journal soft deleted!');
    }

    public function restore($id)
    {
        $journal = JournalEntry::onlyTrashed()->findOrFail($id);
        $journal->restore();

        return redirect()->route('journals.index')->with('success', 'Journal restored!');
    }

    public function hardDelete($id)
    {
        $journal = JournalEntry::onlyTrashed()->findOrFail($id);
        $journal->forceDelete();

        return redirect()->route('journals.index')->with('success', 'Journal permanently deleted!');
    }
}
