@extends('layouts.app')

@section('content')

<div class="container-fluid px-4">

<div class="d-flex justify-content-between align-items-center mb-4">

    <h3 class="fw-bold mb-0">Profiles</h3>

    <!-- ✅ FIXED RIGHT ALIGN BUTTON -->
    <a href="{{ route('users.create') }}" class="btn btn-primary px-4 ms-auto">
        + Create New Profile
    </a>

</div>

<!-- ALERTS -->
@foreach (['success', 'error', 'info'] as $msg)
@if(session($msg))
<div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} shadow-sm">
    {{ session($msg) }}
</div>
@endif
@endforeach

@if($users->isEmpty())

<div class="glass text-center py-5">
    <h5>No profiles yet 😢</h5>
    <p class="text-muted">Create your first profile</p>
</div>

@else

<div class="row">
@foreach($users as $user)

<div class="col-md-6 mb-4">

<div class="card profile-card p-3">

    <div class="d-flex justify-content-between align-items-center">

        <div class="d-flex align-items-center gap-3">

            <div class="avatar">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <div>
                <h5 class="mb-0 fw-semibold">{{ $user->name }}</h5>

                @if(session('active_user') == $user->id)
                    <span class="badge bg-success mt-1">Current Profile</span>
                @endif
            </div>

        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('users.edit', $user->id) }}"
               class="btn btn-sm btn-light px-3">
                Edit
            </a>

            <button type="button"
                    class="btn btn-sm btn-outline-danger px-3"
                    onclick="openDeleteModal({{ $user->id }}, '{{ $user->name }}')">
                Delete
            </button>

        </div>

    </div>

    <div class="mt-4">
        <form action="{{ route('users.switch', $user->id) }}" method="POST">
            @csrf

            <div class="d-flex gap-2">
                <input type="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter password to switch">

                <button type="submit" class="btn btn-primary px-4">
                    Switch
                </button>
            </div>
        </form>
    </div>

</div>

</div>

@endforeach
</div>

@endif

</div>

@endsection

<!-- 🔥 MODAL -->

<div id="deleteModal" class="custom-modal">

<div class="modal-box" onclick="event.stopPropagation()">

    <div class="modal-icon">⚠️</div>

    <h4 class="fw-bold mb-2">Delete Profile</h4>

    <p id="deleteText" class="text-muted mb-4 text-center"></p>

    <form id="deleteForm" method="POST">
        @csrf
        @method('DELETE')

        <div class="d-flex justify-content-center gap-3">
            <button type="button"
                    class="btn btn-light px-4"
                    onclick="closeDeleteModal()">
                Cancel
            </button>

            <button type="submit"
                    class="btn btn-danger px-4">
                Delete
            </button>
        </div>
    </form>

</div>

</div>

<style>

/* CARD */
.profile-card {
    border-radius: 16px;
    border: none;
    box-shadow: 0 6px 18px rgba(0,0,0,0.05);
    transition: 0.2s;
}
.profile-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 22px rgba(0,0,0,0.08);
}

/* AVATAR */
.avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(90deg, #6c63ff, #4facfe);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

/* INPUT */
.form-control {
    border-radius: 10px;
    border: 1px solid #d1d5db;
}

/* BUTTONS */
.btn {
    border-radius: 10px;
}

/* MODAL */
.custom-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;

    background: rgba(0,0,0,0.65);
    display: none;

    z-index: 999999;
}

/* CENTER BOX */
.modal-box {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);

    background: #fff;
    padding: 30px;
    border-radius: 20px;
    width: 100%;
    max-width: 420px;
    text-align: center;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);

    animation: pop 0.2s ease;
}

@keyframes pop {
    from {
        opacity: 0;
        transform: translate(-50%, -40%) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
    }
}

.modal-icon {
    width: 65px;
    height: 65px;
    margin: 0 auto 15px;
    border-radius: 50%;
    background: #ffe5e5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
}

</style>

<script>

function openDeleteModal(id, name) {
    const modal = document.getElementById('deleteModal');

    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';

    // ✅ BETTER MESSAGE WITH NAME
    document.getElementById('deleteText').innerText =
        `Are you sure you want to delete "${name}"?\nThis action cannot be undone. 😢`;

    document.getElementById('deleteForm').action = "/users/" + id;
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');

    modal.style.display = 'none';

    // ✅ FIX DARK SCREEN BUG
    document.body.style.overflow = 'auto';
}

/* CLICK OUTSIDE CLOSE */
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

/* ✅ ESC KEY CLOSE (EXTRA UX FIX) */
document.addEventListener('keydown', function(e) {
    if (e.key === "Escape") {
        closeDeleteModal();
    }
});

</script>