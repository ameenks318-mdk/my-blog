<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Blog</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/myblog/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/myblog/index.php">📝 My Blog</a>
        <div class="ms-auto d-flex align-items-center">

            <!-- Dark Mode Toggle -->
            <div class="dark-toggle me-3">
                <label>🌙 Dark</label>
                <label class="switch">
                    <input type="checkbox" id="darkToggle">
                    <span class="slider"></span>
                </label>
            </div>

            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="text-white me-3">
                    Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                </span>
                <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
                    <a href="/myblog/admin/users.php" class="btn btn-danger btn-sm me-2">👑 Admin</a>
                <?php endif; ?>
                <a href="/myblog/auth/logout.php" class="btn btn-outline-warning btn-sm">Logout</a>
            <?php else: ?>
                <a href="/myblog/auth/login.php" class="btn btn-outline-light btn-sm me-2">Login</a>
                <a href="/myblog/auth/register.php" class="btn btn-warning btn-sm">Register</a>
            <?php endif; ?>

        </div>
    </div>
</nav>

<script>
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
        document.getElementById('darkToggle').checked = true;
    }
    document.getElementById('darkToggle').addEventListener('change', function() {
        if (this.checked) {
            document.body.classList.add('dark-mode');
            localStorage.setItem('darkMode', 'true');
        } else {
            document.body.classList.remove('dark-mode');
            localStorage.setItem('darkMode', 'false');
        }
    });
</script>