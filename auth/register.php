<?php
session_start();
require_once '../config/db.php';

$errors = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $pass     = $_POST['password'] ?? '';

    if (empty($username))
        $errors[] = 'Username is required.';
    elseif (strlen($username) > 100)
        $errors[] = 'Username too long (max 100 chars).';

    if (strlen($pass) < 8)
        $errors[] = 'Password must be at least 8 characters.';
    if (!preg_match('/[A-Z]/', $pass))
        $errors[] = 'Password needs at least one uppercase letter.';
    if (!preg_match('/[0-9]/', $pass))
        $errors[] = 'Password needs at least one number.';

    if (empty($errors)) {
        $chk = $pdo->prepare('SELECT id FROM users WHERE username = ?');
        $chk->execute([$username]);
        if ($chk->fetch())
            $errors[] = 'Username already taken.';
    }

    if (empty($errors)) {
        $hashed = password_hash($pass, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashed]);
        header("Location: /myblog/auth/login.php");
        exit();
    }
}
include '../header.php';
?>

<div class="container mt-4" style="max-width:450px">
    <h2 class="mb-4">Register</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $err): ?>
                    <li><?php echo htmlspecialchars($err); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" id="regForm" novalidate>
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control"
                   value="<?php echo htmlspecialchars($username); ?>"
                   minlength="2" maxlength="100" required>
            <span class="text-danger err"></span>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control"
                   pattern="(?=.*[A-Z])(?=.*\d).{8,}" required>
            <span class="text-danger err"></span>
            <small class="text-muted">Min 8 chars, one uppercase, one number</small>
        </div>
        <button type="submit" class="btn btn-warning w-100">Register</button>
        <p class="mt-2 text-center">Already have account?
            <a href="/myblog/auth/login.php">Login</a>
        </p>
    </form>
</div>

<script src="/myblog/js/validation.js"></script>
<?php include '../footer.php'; ?>