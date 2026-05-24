<?php
session_start();
require_once '../config/db.php';
require_once '../helpers/auth.php';
requireRole('admin');
include '../header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->execute([$_POST['role'], $_POST['user_id']]);
    header("Location: /myblog/admin/users.php");
    exit();
}

$users = $pdo->query("SELECT * FROM users ORDER BY id ASC")->fetchAll();
?>

<div class="container mt-4">
    <h2>👑 Admin Panel — Manage Users</h2>
    <a href="/myblog/index.php" class="btn btn-secondary btn-sm mb-3">← Back to Blog</a>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Change Role</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td>
                    <span class="badge
                        <?php echo $user['role'] === 'admin' ? 'bg-danger' :
                            ($user['role'] === 'editor' ? 'bg-warning text-dark'
                            : 'bg-secondary'); ?>">
                        <?php echo $user['role']; ?>
                    </span>
                </td>
                <td>
                    <form method="POST" class="d-flex gap-2">
                        <input type="hidden" name="user_id"
                               value="<?php echo $user['id']; ?>">
                        <select name="role" class="form-select form-select-sm">
                            <option value="user"
                                <?php echo $user['role']==='user' ? 'selected':''; ?>>
                                user</option>
                            <option value="editor"
                                <?php echo $user['role']==='editor' ? 'selected':''; ?>>
                                editor</option>
                            <option value="admin"
                                <?php echo $user['role']==='admin' ? 'selected':''; ?>>
                                admin</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../footer.php'; ?>