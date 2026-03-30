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
        $activeUser = User::find($activeUserId);

        if (!User::count()) {
            return redirect()->route('users.create')->with('info', 'Please create a profile first.');
        }

        if (!$activeUserId) {
            return redirect()->route('users.index')->with('info', 'Please select a profile.');
        }

        // ✅ FIXED INPUT NAMES (MATCH FRONTEND)
        $search = $request->input('search');
        $mood = $request->input('mood');
        $dateFrom = $request->input('from');
        $dateTo = $request->input('to');
        $range = $request->input('range');

        $query = JournalEntry::where('user_id', $activeUserId);

        // 🔍 SEARCH (FIXED GROUPING)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('mood', 'like', "%{$search}%");
            });
        }

        // 😊 MOOD
        if ($mood) {
            $query->where('mood', $mood);
        }

        // ⚡ RANGE FILTERS
        if ($range) {

            if ($range == 'today') {
                $query->whereDate('date', now());
            }

            if ($range == '7days') {
                $query->whereDate('date', '>=', now()->subDays(7));
            }

            if ($range == '30days') {
                $query->whereDate('date', '>=', now()->subDays(30));
            }

            if ($range == 'month') {
                $query->whereMonth('date', now()->month)
                      ->whereYear('date', now()->year);
            }

            if ($range == 'year') {
                $query->whereYear('date', now()->year);
            }
        }

        // 📅 CUSTOM DATE FILTER (FIXED)
        if ($dateFrom) {
            $query->whereDate('date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('date', '<=', $dateTo);
        }

        // 📄 RESULT
        $journals = $query->orderBy('date', 'desc')->get();

        // 🗂 TRASHED
        $trashedJournals = JournalEntry::onlyTrashed()
            ->where('user_id', $activeUserId)
            ->orderBy('deleted_at', 'desc')
            ->get();

        return view('journals.index', compact(
            'journals',
            'trashedJournals',
            'activeUser'
        ));
    }

    public function create()
    {
        $users = User::all();
        return view('journals.create', compact('users'));
    }

    public function store(Request $request)
    {
        $activeUserId = session('active_user');

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
            'location' => 'required|string|max:255',
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