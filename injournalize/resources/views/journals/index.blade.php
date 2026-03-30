@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('content')

<!-- WELCOME -->
<div class="glass mb-4">
    <h2 class="fw-bold mb-1">
        Welcome back, {{ $activeUser->name ?? 'Guest' }} 👋
    </h2>
    <p class="text-muted mb-0">Here’s a look at your journals</p>
</div>

<!-- FILTER BAR -->
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

    <div class="d-flex align-items-center gap-2 flex-wrap">

        <a href="{{ route('journals.index') }}"
           class="filter-chip {{ !request('range') ? 'active' : '' }}">
            All
        </a>

        <a href="{{ route('journals.index', ['range'=>'today']) }}"
           class="filter-chip {{ request('range')=='today' ? 'active' : '' }}">
            Today
        </a>

        <a href="{{ route('journals.index', ['range'=>'7days']) }}"
           class="filter-chip {{ request('range')=='7days' ? 'active' : '' }}">
            Last 7 days
        </a>

        <a href="{{ route('journals.index', ['range'=>'30days']) }}"
           class="filter-chip {{ request('range')=='30days' ? 'active' : '' }}">
            Last 30 days
        </a>

        <a href="{{ route('journals.index', ['range'=>'month']) }}"
           class="filter-chip {{ request('range')=='month' ? 'active' : '' }}">
            This month
        </a>

        <a href="{{ route('journals.index', ['range'=>'year']) }}"
           class="filter-chip {{ request('range')=='year' ? 'active' : '' }}">
            This year
        </a>

        <button class="advanced-link" onclick="toggleFilters()">
            Advanced filters
        </button>

    </div>

    <!-- RIGHT SIDE -->
    <div class="d-flex flex-wrap gap-2 action-group">

        <!-- SEARCH -->
        <form method="GET" action="{{ route('journals.index') }}" class="search-box">

            <span class="search-icon">🔍</span>

            <input type="text"
                   name="search"
                   class="search-input"
                   placeholder="Search journals..."
                   value="{{ request('search') }}">

            <button type="submit" class="btn btn-primary btn-uniform">
                Search
            </button>

        </form>

        <!-- NEW ENTRY -->
        <a href="{{ route('journals.create') }}" class="btn btn-primary btn-uniform">
            + New Entry
        </a>

    </div>

</div>

<!-- ADVANCED FILTERS -->
<div id="advancedFilters" class="glass mb-4"
     style="{{ request()->has('from') || request()->has('to') || request()->has('mood') ? 'display:block;' : 'display:none;' }}">

    <form method="GET" action="{{ route('journals.index') }}">

        <div class="row">

            <div class="col-md-4 mb-3">
                <label class="form-label small text-muted">From Date</label>
                <input type="date" name="from" class="form-control" value="{{ request('from') }}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label small text-muted">To Date</label>
                <input type="date" name="to" class="form-control" value="{{ request('to') }}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label small text-muted">Mood</label>
                <select name="mood" class="form-select">
                    <option value="">All</option>
                    <option value="happy" {{ request('mood')=='happy'?'selected':'' }}>😊 Happy</option>
                    <option value="sad" {{ request('mood')=='sad'?'selected':'' }}>😢 Sad</option>
                    <option value="angry" {{ request('mood')=='angry'?'selected':'' }}>😡 Angry</option>
                </select>
            </div>

        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('journals.index') }}" class="btn btn-light">
                Reset
            </a>

            <button type="submit" class="btn btn-primary">
                Apply Filters
            </button>
        </div>

    </form>

</div>

<!-- JOURNALS -->
<h4 class="fw-semibold mb-3">Your Journals</h4>

@if($journals->isEmpty())
<div class="glass text-center py-5">
    <h5>No journals found</h5>
</div>
@else

<div class="row">
@foreach($journals as $journal)

@php
    $emoji = match($journal->mood) {
        'happy' => '😊',
        'sad' => '😢',
        'angry' => '😡',
        default => '😐'
    };
@endphp

<div class="col-md-4 mb-4">

    <div class="card journal-card p-3">

        <small class="text-muted">
            {{ $journal->date->format('M d, Y') }}
        </small>

        <h5 class="fw-bold mt-1 mb-2">
            {{ $journal->title }}
        </h5>

        <p class="text-muted small">📍 {{ $journal->location }}</p>

        <p class="text-muted small">
            {{ Str::limit($journal->content, 90) }}
        </p>

        <div class="d-flex justify-content-between align-items-center mt-3">

            <span class="mood-badge">
                {{ $emoji }} {{ ucfirst($journal->mood) }}
            </span>

            <div class="action-buttons">

                <!-- EDIT -->
                <a href="{{ route('journals.edit', $journal->id) }}"
                   class="btn btn-outline-secondary action-btn">
                    Edit
                </a>

                <!-- DELETE -->
                <form action="{{ route('journals.destroy', $journal->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                        class="btn btn-outline-danger action-btn"
                        onclick="openDeleteModal(this.closest('form'))">
                        Delete
                    </button>
                </form>

            </div>

        </div>

    </div>

</div>
@endforeach
</div>

@endif

<!-- 🗑 DELETED JOURNALS -->
<h5 class="mt-5 mb-3 fw-semibold text-danger">🗑 Deleted Journals</h5>

@if($trashedJournals->isEmpty())
<p class="text-muted">No deleted journals.</p>
@else

<div class="row">
@foreach($trashedJournals as $journal)
<div class="col-md-4 mb-3">

    <div class="card p-3 border border-danger-subtle">

        <small class="text-muted">
            {{ $journal->date->format('M d, Y') }}
        </small>

        <h6 class="fw-bold mt-1">
            {{ $journal->title }}
        </h6>

        <p class="text-muted small">
            {{ Str::limit($journal->content, 60) }}
        </p>

        <div class="d-flex justify-content-between align-items-center mt-2">

            <form action="{{ route('journals.restore', $journal->id) }}" method="POST">
                @csrf
                @method('PUT')
                <button class="btn btn-sm btn-success">
                    Restore
                </button>
            </form>

            <form action="{{ route('journals.hardDelete', $journal->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">
                    Delete Permanently
                </button>
            </form>

        </div>

    </div>

</div>
@endforeach
</div>

@endif

@endsection

<style>

/* 🔥 CRITICAL ALIGNMENT FIX */
.action-group {
    display: flex;
    align-items: stretch; /* THIS FIXES YOUR ISSUE */
    gap: 10px;
}

/* BUTTONS SAME HEIGHT */
.btn-uniform {
    height: 44px;
    padding: 0 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    white-space: nowrap;
}

/* SEARCH BOX */
.search-box {
    display: flex;
    align-items: center;
    height: 44px;
    background: white;
    border-radius: 12px;
    padding: 0 10px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 12px rgba(0,0,0,0.04);
}

/* SEARCH BUTTON FIX */
.search-box button {
    height: 100%;
    display: flex;
    align-items: center;
}

/* INPUT */
.search-input {
    border: none;
    outline: none;
    height: 100%;
    padding: 0 10px;
    background: transparent;
    min-width: 200px;
}

.search-icon {
    display: flex;
    align-items: center;
}

/* EDIT DELETE ALIGN */
.action-buttons {
    display: flex;
    align-items: center;
    gap: 8px;
}

.action-btn {
    height: 34px;
    padding: 0 16px;
    border-radius: 8px;
    font-size: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* FILTER */
.filter-chip {
    padding: 6px 12px;
    border-radius: 20px;
    background: #f3f4f6;
    text-decoration: none;
    color: #374151;
    font-size: 13px;
}

.filter-chip.active {
    background: linear-gradient(90deg, #6c63ff, #4facfe);
    color: white;
}

.advanced-link {
    border: none;
    background: none;
    font-size: 13px;
    color: #6b7280;
}

</style>

<script>
function toggleFilters() {
    const panel = document.getElementById('advancedFilters');
    if (panel) {
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    }
}
</script>