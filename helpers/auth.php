<?php
function requireLogin(): void {
    if (empty($_SESSION['user_id'])) {
        header('Location: ../login.php');
        exit;
    }
}

function requireRole(string ...$roles): void {
    requireLogin();
    if (!in_array($_SESSION['user_role'] ?? '', $roles)) {
        http_response_code(403);
        die('
        <div style="text-align:center; padding:50px; font-family:Arial">
            <h1 style="color:red">403 - Access Denied</h1>
            <p>You do not have permission to view this page.</p>
            <a href="../index.php">Go Back Home</a>
        </div>
        ');
    }
}

function isAdmin(): bool {
    return ($_SESSION['user_role'] ?? '') === 'admin';
}

function isEditor(): bool {
    return in_array($_SESSION['user_role'] ?? '', ['admin', 'editor']);
}
?>