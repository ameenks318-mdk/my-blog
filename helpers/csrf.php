<?php
function csrf_token(): string {
    return $_SESSION['csrf_token'] ?? '';
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token"
            value="' . csrf_token() . '">';
}

function verify_csrf(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            die('
            <div style="text-align:center; padding:50px; font-family:Arial">
                <h1 style="color:red">403 - Invalid CSRF Token</h1>
                <p>Form submission rejected for security reasons.</p>
                <a href="index.php">Go Back Home</a>
            </div>
            ');
        }
    }
}
?>