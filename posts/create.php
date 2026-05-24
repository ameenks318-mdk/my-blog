<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /myblog/auth/login.php");
    exit();
}
require_once '../config/db.php';
require_once '../helpers/csrf.php';
verify_csrf();

$errors = [];
$title = '';
$content = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title   = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if (empty($title))   $errors[] = 'Title is required.';
    if (empty($content)) $errors[] = 'Content is required.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
        $stmt->execute([$title, $content]);
        header("Location: /myblog/index.php");
        exit();
    }
}
include '../header.php';
?>

<div class="container mt-4">
    <h2>Create New Post</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $err): ?>
                    <li><?php echo htmlspecialchars($err); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="/myblog/posts/create.php">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control"
                   value="<?php echo htmlspecialchars($title); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" class="form-control"
                      rows="5"><?php echo htmlspecialchars($content); ?></textarea>
        </div>
        <button type="submit" class="btn btn-success">Publish Post</button>
        <a href="/myblog/index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../footer.php'; ?>