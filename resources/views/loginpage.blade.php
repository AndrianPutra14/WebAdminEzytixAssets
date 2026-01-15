<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Font (AdminLTE Style) -->
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
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
            background-color: var(--bg-dark);
            min-height: 100vh;
            font-family: 'Source Sans 3', sans-serif;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login-card {
            width: 100%;
            max-width: 360px;
            background-color: var(--card-dark);
            border-radius: 8px;
            box-shadow: 0 15px 35px rgba(0,0,0,.6);
            color: var(--text-main);
        }

        .login-header {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid var(--border-dark);
        }

        .login-header h4 {
            margin-bottom: .25rem;
            font-weight: 700;
            color: var(--primary);
        }

        .login-header small {
            color: var(--text-muted);
        }

        .login-body {
            padding: 1.5rem;
        }

        .form-label {
            color: var(--text-muted);
            font-weight: 600;
            font-size: .9rem;
        }

        .form-control {
            background-color: var(--bg-dark);
            border: 1px solid var(--border-dark);
            color: var(--text-main);
        }

        .form-control::placeholder {
            color: #90a4ae;
        }

        .form-control:focus {
            background-color: var(--bg-dark);
            border-color: var(--primary);
            box-shadow: none;
            color: #fff;
        }

        .input-group-text {
            background-color: var(--bg-dark);
            border: 1px solid var(--border-dark);
            color: var(--text-muted);
            cursor: pointer;
        }

        .btn-login {
            background-color: var(--primary);
            border: none;
            font-weight: 600;
        }

        .btn-login:hover {
            background-color: var(--primary-dark);
        }

        .login-footer {
            border-top: 1px solid var(--border-dark);
            padding: 1rem;
            text-align: center;
            font-size: .85rem;
            color: var(--text-muted);
        }
    </style>
</head>

<body>

<div class="login-wrapper">
    <div class="login-card">

        <!-- Header -->
        <div class="login-header">
            <h4><i class="bi bi-shield-lock"></i> Ezytix</h4>
            <small>Silakan login untuk melanjutkan</small>
        </div>

        <!-- Body -->
        <div class="login-body">
            <form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
    </div>

    <div class="mb-4">
        <label class="form-label">Password</label>
        <div class="input-group">
            <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
            <span class="input-group-text" id="togglePassword">
                <i class="bi bi-eye"></i>
            </span>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-3 text-danger small">
            {{ $errors->first() }}
        </div>
    @endif

    <button type="submit" class="btn btn-login w-100">
        <i class="bi bi-box-arrow-in-right me-1"></i> Login
    </button>
</form>

        </div>

        <!-- Footer -->
        <div class="login-footer">
            © 2026 Admin Page
        </div>

    </div>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const pwd = document.getElementById('password');
        const icon = this.querySelector('i');

        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            pwd.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    });
</script>

</body>
</html>
