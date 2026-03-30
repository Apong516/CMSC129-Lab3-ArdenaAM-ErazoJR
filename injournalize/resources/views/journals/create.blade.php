@extends('layouts.app')

@section('content')

<div class="journal-wrapper">

    <div class="glass mb-4 animate-fade">
        <h3 class="fw-bold mb-1">✍️ Add New Journal Entry</h3>
        <p class="text-muted mb-0">Capture your thoughts and moments</p>
    </div>

    <div class="card p-4 animate-slide">

        <form method="POST" action="{{ route('journals.store') }}">
            @csrf

            <!-- TITLE -->
            <div class="form-floating mb-3">
                <input type="text" name="title"
                       class="form-control @error('title') is-invalid @enderror"
                       placeholder="Title"
                       value="{{ old('title') }}">
                <label>Title</label>

                @error('title')
                    <div class="input-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- LOCATION -->
            <div class="form-floating mb-3">
                <input type="text" name="location"
                       class="form-control @error('location') is-invalid @enderror"
                       placeholder="Location"
                       value="{{ old('location') }}">
                <label>Location</label>

                @error('location')
                    <div class="input-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- CONTENT -->
            <div class="form-floating mb-3">
                <textarea name="content"
                          class="form-control content-box @error('content') is-invalid @enderror"
                          placeholder="Content">{{ old('content') }}</textarea>
                <label>Write your thoughts...</label>

                @error('content')
                    <div class="input-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- DATE + MOOD -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label small text-muted">Date</label>
                    <input type="date" name="date"
                           class="form-control modern-input @error('date') is-invalid @enderror"
                           value="{{ old('date') }}">

                    @error('date')
                        <div class="input-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label small text-muted">Mood</label>
                    <select name="mood"
                            class="form-select modern-input @error('mood') is-invalid @enderror">
                        <option value="">Select mood</option>
                        <option value="happy" {{ old('mood')=='happy'?'selected':'' }}>😊 Happy</option>
                        <option value="sad" {{ old('mood')=='sad'?'selected':'' }}>😢 Sad</option>
                        <option value="angry" {{ old('mood')=='angry'?'selected':'' }}>😡 Angry</option>
                    </select>

                    @error('mood')
                        <div class="input-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- ACTIONS -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('journals.index') }}" class="btn btn-light px-4">
                    Cancel
                </a>

                <button type="submit" class="btn btn-primary px-4 save-btn">
                    Save Journal
                </button>
            </div>

        </form>

    </div>

</div>

@endsection

<style>

.journal-wrapper {
    width: 100%;
}

.content-box {
    height: 180px !important;
}

.modern-input {
    border-radius: 12px;
    padding: 10px;
}

.form-floating > .form-control,
.form-floating > .form-control:focus {
    border-radius: 12px;
}

/* 🔥 CLEAN ERROR STYLE */
.input-error {
    font-size: 13px;
    color: #ef4444;
    margin-top: 6px;
    padding-left: 5px;
}

/* INPUT ERROR STATE */
.is-invalid {
    border-color: #ef4444 !important;
    background: #fef2f2;
}

/* ANIMATIONS */
.animate-fade {
    animation: fadeIn 0.6s ease;
}

.animate-slide {
    animation: slideUp 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* BUTTON EFFECT */
.save-btn {
    transition: 0.2s;
}

.save-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(79,70,229,0.3);
}

/* FOCUS */
.form-control:focus, .form-select:focus {
    border-color: #6c63ff;
    box-shadow: 0 0 0 2px rgba(108,99,255,0.15);
}

</style>