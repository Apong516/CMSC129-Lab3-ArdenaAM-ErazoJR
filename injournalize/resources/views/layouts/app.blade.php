<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InJournalize</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fb;
        }

        /* SIDEBAR */
        .sidebar {
            width: 240px;
            height: 100vh;
            position: fixed;
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            padding: 20px;
        }

        .sidebar h5 {
            font-weight: 700;
            margin-bottom: 25px;
        }

        .sidebar .nav-link {
            color: #6b7280;
            padding: 10px 14px;
            border-radius: 12px;
            margin-bottom: 8px;
            display: block;
            transition: 0.2s;
        }

        .sidebar .nav-link:hover {
            background: #eef2ff;
            color: #4f46e5;
        }

        .sidebar .active {
            background: linear-gradient(90deg, #6c63ff, #4facfe);
            color: white !important;
        }

        /* CONTENT */
        .content {
            margin-left: 240px;
            padding: 30px;
        }

        /* GLASS */
        .glass {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.05);
        }

        /* CARD */
        .card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 6px 20px rgba(0,0,0,0.05);
        }

        .journal-card {
            transition: 0.25s;
        }

        .journal-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.08);
        }

        /* BUTTONS */
        .btn {
            border-radius: 10px;
            font-weight: 500;
        }

        .btn-primary {
            background: linear-gradient(90deg, #6c63ff, #4facfe);
            border: none;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .btn-light {
            background: #f3f4f6;
            border: none;
        }

        .btn-light:hover {
            background: #e5e7eb;
        }

        /* INPUT */
        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #d1d5db;
        }

        /* BADGE */
        .mood-badge {
            background: #f3f4f6;
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 12px;
        }

        /* USER DOT */
        .user-dot {
            width: 10px;
            height: 10px;
            background: #4f46e5;
            border-radius: 50%;
        }

        /* 🔥 TOAST */
        #toastContainer {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
        }

        .toast-box {
            min-width: 250px;
            padding: 12px 18px;
            border-radius: 10px;
            color: white;
            margin-top: 10px;
            font-size: 14px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            animation: fadeInUp 0.4s ease;
        }

        .toast-success { background: #10b981; }
        .toast-error { background: #ef4444; }
        .toast-info { background: #3b82f6; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>

<div class="sidebar">
    <h5>📓 InJournalize</h5>

    <a href="{{ route('journals.index') }}"
       class="nav-link {{ request()->routeIs('journals.index') ? 'active' : '' }}">
        🏠 Dashboard
    </a>

    <a href="{{ route('journals.create') }}"
       class="nav-link {{ request()->routeIs('journals.create') ? 'active' : '' }}">
        ➕ New Entry
    </a>

    <a href="#"
       class="nav-link {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
        📅 Calendar
    </a>

    <a href="#"
       class="nav-link {{ request()->routeIs('favorites.*') ? 'active' : '' }}">
        ❤️ Favorites
    </a>

    <a href="{{ route('users.index') }}"
       class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
        ⚙️ Profiles
    </a>
</div>

<div class="content">

    <!-- 🔥 TOAST NOTIFICATIONS -->
    @if(session('success') || session('error') || session('info'))
        <div id="toastContainer">

            @if(session('success'))
                <div class="toast-box toast-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="toast-box toast-error">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('info'))
                <div class="toast-box toast-info">
                    {{ session('info') }}
                </div>
            @endif

        </div>
    @endif

    @yield('content')

</div>

<!-- DELETE MODAL -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Delete Journal</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                Are you sure you want to delete this entry?
            </div>

            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
            </div>

        </div>
    </div>
</div>

<script>
let deleteForm = null;

function openDeleteModal(form) {
    deleteForm = form;
    let modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', function () {

    // DELETE CONFIRM
    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (deleteForm) deleteForm.submit();
    });

    // 🔥 AUTO HIDE TOAST
    setTimeout(() => {
        const toast = document.getElementById('toastContainer');
        if (toast) {
            toast.style.opacity = '0';
            toast.style.transition = '0.5s';
            setTimeout(() => toast.remove(), 500);
        }
    }, 3000);

});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>