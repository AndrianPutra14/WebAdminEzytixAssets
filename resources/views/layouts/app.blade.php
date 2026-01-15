<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/logo.jpeg') }}" type="image/jpeg">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    

    <style>
        /* ===== COLOR SYSTEM ===== */
        :root {
            --primary: #D32F2F;
            --primary-light: #FF6659;
            --primary-dark: #9A0007;

            --bg-dark: #1f2d3d;
            --card-dark: #2c3b4d;
            --border-dark: #3c4b5d;
            --text-main: #ecf0f1;
            --text-muted: #b0bec5;
        }

        body {
            margin: 0;
            background: #3c4b5d;
            font-family: 'Source Sans 3', sans-serif;
            overflow-x: hidden;
        }

        .mobile-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.4);
    z-index: 1049;
    display: none;
}

.mobile-overlay.show {
    display: block;
}


        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--bg-dark);
            border-right: 1px solid var(--border-dark);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        @media (max-width: 991.98px) {
    .sidebar {
        left: -260px;
        z-index: 1050;
        transition: left .3s ease;
    }

    .sidebar.show {
        left: 0;
    }

    .content {
        margin-left: 0 !important;
        padding: 20px;
        width: 100vw;
        max-width: 100vw;
        overflow-x: hidden;
    }
}

        .sidebar-header {
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border-dark);
        }

        .sidebar-header small {
            color: var(--text-muted);
        }

        .sidebar .nav-link {
            color: var(--text-main);
            border-radius: 10px;
            padding: 10px 14px;
            transition: all .2s ease;
        }

        .sidebar .nav-link i {
            font-size: 18px;
        }

        .sidebar .nav-link.active {
            background: linear-gradient(
                135deg,
                var(--primary),
                var(--primary-dark)
            );
            color: #fff;
        }

        .sidebar .nav-link:hover {
            background: var(--card-dark);
            color: #fff;
        }

        .sidebar-footer {
            border-top: 1px solid var(--border-dark);
            padding-top: 15px;
        }

        .user-box small {
            color: var(--text-muted);
        }

        /* ===== CONTENT ===== */
        .content {
            margin-left: 260px;
            padding: 30px;
            min-height: 100vh;
            
        }

        /* STAT CARD */
.stat-card {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 18px;
}

.stat-card h3 {
  margin: 8px 0;
  font-weight: 700;
}

.stat-card small {
  color: #6b7280;
}

/* TICKET LIST */
.ticket-item {
  display: flex;
  justify-content: space-between;
  padding: 14px;
  border: 1px solid #e5e7eb;
  border-radius: 10px;
  margin-bottom: 12px;
}

.ticket-id {
  font-weight: 600;
  margin-right: 8px;
}

.ticket-date {
  font-size: 12px;
  color: #6b7280;
  margin-top: 4px;
}

.stat-card{
    background-color: var(--primary-dark);
    color: var(--text-main);
    border-color: var(--border-dark);

}

small.text-all {
    color: var(--text-main);
}

.card-custom {
    background-color: var(--bg-dark); /* atau merah */
    color: var(--text-main);
    border: 1px solid var(--border-dark);
}

.form-label {
    font-size: 14px;
    font-weight: 600;
}

.form-control,
.form-select {
    border-radius: 8px;
    font-size: 14px;
}

/* Badge Priority */
.badge-urgent {
    background: #fee2e2;
    color: #b91c1c;
}

.badge-high {
    background: #ffedd5;
    color: #c2410c;
}

.badge-medium {
    background: #fef9c3;
    color: #a16207;
}

.badge-low {
    background: #dcfce7;
    color: #166534;
}

/* Badge Status */
.badge-open {
    background: #dbeafe;
    color: #1d4ed8;
}

.badge-progress {
    background: #fef3c7;
    color: #92400e;
}

.badge-resolved {
    background: #dcfce7;
    color: #166534;
}

.badge-closed {
    background: #f3f4f6;
    color: #374151;
}

.badge {
    font-size: 12px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 999px;
}

.border-custom{
    border-color: var(--primary-dark);
}

/* CARD DARK */
.card-dark {
    background-color: var(--bg-dark);
    border: 1px solid var(--border-dark);
    color: var(--text-main);
    border-radius: 14px;
    overflow: hidden;
}

/* ACTION ICON */
.card-dark .table a,
.card-dark .table i {
    color: var(--text-main);
}

.card-dark .table a:hover {
    color: var(--primary-light);
}
/* OVERRIDE BOOTSTRAP TABLE BACKGROUND */
.card-dark .table {
    --bs-table-bg: transparent !important;
    --bs-table-striped-bg: transparent !important;
    --bs-table-hover-bg: rgba(255,255,255,0.05) !important;
    color: var(--text-main);
}
/* THEAD MERAH */
.card-dark thead {
    background: linear-gradient(
        135deg,
        var(--primary),
        var(--primary-dark)
    ) !important;
}

.card-dark thead th {
    color: #fff !important;
    border-bottom: 1px solid rgba(255,255,255,0.2) !important;
}

/* FORCE CARD BODY DARK */
.card-dark .card-body {
    background-color: var(--bg-dark) !important;
}

/* FORCE TABLE DARK */
.card-dark table {
    background-color: var(--bg-dark) !important;
    color: var(--text-main) !important;
}


/* TBODY */
.card-dark tbody tr {
    background-color: transparent !important;
}

.card-dark tbody tr:hover {
    background-color: rgba(255,255,255,0.05) !important;
}

/* BORDER */
.card-dark td,
.card-dark th {
    border-color: var(--border-dark) !important;
}

.card-dark tbody td {
    color: var(--text-main) !important;
}

    /* ===== History ===== */
.timeline {
    position: relative;
    margin-left: 10px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 14px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--border-dark);
}

.timeline-item {
    position: relative;
    display: flex;
    gap: 20px;
    padding-left: 40px;
    margin-bottom: 25px;
}

.timeline-dot {
    position: absolute;
    left: 6px;
    top: 8px;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    border: 3px solid var(--bg-dark);
}

.timeline-content {
    background: var(--card-dark);
    border: 1px solid var(--border-dark);
    border-radius: 12px;
    padding: 14px 16px;
    width: 100%;
}

/* MOBILE */
@media (max-width: 576px) {
    .timeline-content {
        font-size: 14px;
    }
}
/* manajemen user */
.btn-primary-custom {
    background: var(--primary);
    border-color: var(--primary);
    color: #fff;
}

.btn-primary-custom:hover {
    background: var(--primary-dark);
}

.modal-content.card-dark {
    border-radius: 16px;
}

/* profile */
.avatar-wrapper {
    display: flex;
    justify-content: center;
}

.avatar {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    background: linear-gradient(135deg, #d32f2f, #ff5252);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 64px;
    color: #fff;
}

    </style>
</head>
<body>

    {{-- SIDEBAR --}}
    @include('partials.sidebar')

    {{-- OVERLAY MOBILE --}}
    <div class="mobile-overlay" id="overlay"></div>
    {{-- CONTENT --}}
    <div class="content">
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
const toggleBtn = document.getElementById('toggleSidebar');
const sidebar = document.querySelector('.sidebar');
const overlay = document.getElementById('overlay');

toggleBtn?.addEventListener('click', () => {
    sidebar.classList.toggle('show');
    overlay.classList.toggle('show');
});

overlay?.addEventListener('click', () => {
    sidebar.classList.remove('show');
    overlay.classList.remove('show');
});
</script>

</body>
</html>
