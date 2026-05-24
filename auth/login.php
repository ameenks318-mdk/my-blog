<?php
session_start();
require_once '../config/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $pass     = $_POST['password'] ?? '';

    if (empty($username)) $errors[] = 'Username is required.';
    if (empty($pass))     $errors[] = 'Password is required.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $row = $stmt->fetch();

        if ($row && password_verify($pass, $row['password'])) {
            $_SESSION['user_id']   = $row['id'];
            $_SESSION['username']  = $row['username'];
            $_SESSION['user_role'] = $row['role'] ?? 'user';
            header("Location: /myblog/index.php");
            exit();
        } else {
            $errors[] = 'Wrong username or password.';
        }
    }
}
include '../header.php';
?>

<div class="container mt-4" style="max-width:450px">
    <h2 class="mb-4">Login</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $err): ?>
                    <li><?php echo htmlspecialchars($err); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" id="loginForm" novalidate>
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control"
                   value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
            <span class="text-danger err"></span>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
            <span class="text-danger err"></span>
        </div>
        <button type="submit" class="btn btn-dark w-100">Login</button>
        <p class="mt-2 text-center">No account?
            <a href="/myblog/auth/register.php">Register</a>
        </p>
    </form>
</div>

<script src="/myblog/js/validation.js"></script>
<?php include '../footer.php'; ?>