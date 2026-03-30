@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <div class="card shadow-sm">
        <div class="card-header fw-bold">
            ✏️ Edit Journal Entry
        </div>

        <div class="card-body">

            <!-- Errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Oops!</strong> Please fix the following:
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('journals.update', $journal->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Title</label>
                    <input type="text" name="title" 
                        value="{{ old('title', $journal->title) }}" 
                        class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Content</label>
                    <textarea name="content" class="form-control" rows="5" required>
{{ old('content', $journal->content) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Date</label>
                        <input type="date" name="date" 
                            value="{{ old('date', $journal->date->format('Y-m-d')) }}" 
                            class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Mood</label>
                        <select name="mood" class="form-select" required>
                            @foreach(['happy','neutral','sad','angry'] as $mood)
                                <option value="{{ $mood }}" 
                                    @if(old('mood', $journal->mood) == $mood) selected @endif>
                                    {{ ucfirst($mood) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('journals.index') }}" class="btn btn-secondary">
                        ← Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        💾 Update Journal
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection