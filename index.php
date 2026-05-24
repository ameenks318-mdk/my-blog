<?php
require_once 'config/db.php';
include 'header.php';

$q = isset($_GET['q']) ? $_GET['q'] : '';
$search = "%$q%";

$posts_per_page = 3;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $posts_per_page;

$count_stmt = $pdo->prepare("SELECT COUNT(*) as total FROM posts WHERE title LIKE ? OR content LIKE ?");
$count_stmt->execute([$search, $search]);
$total_posts = $count_stmt->fetch()['total'];
$total_pages = ceil($total_posts / $posts_per_page);

$stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE ? OR content LIKE ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
$stmt->execute([$search, $search, $posts_per_page, $offset]);
$posts = $stmt->fetchAll();
?>

<div class="container mt-4">

    <form method="GET" class="d-flex mb-4">
        <input type="text" name="q" class="form-control me-2"
               placeholder="Search posts..." value="<?php echo htmlspecialchars($q); ?>">
        <button type="submit" class="btn btn-dark">🔍 Search</button>
    </form>

    <a href="/myblog/posts/create.php" class="btn btn-success mb-4">+ New Post</a>

    <?php if (empty($posts)): ?>
        <div class="alert alert-warning">No posts found.</div>
    <?php else: ?>
        <?php foreach ($posts as $row): ?>
            <div class="post-card">
                <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                <p><?php echo htmlspecialchars($row['content']); ?></p>
                <a href="/myblog/posts/edit.php?id=<?php echo $row['id']; ?>"
                   class="btn btn-primary btn-sm">✏️ Edit</a>
                <a href="/myblog/posts/delete.php?id=<?php echo $row['id']; ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Are you sure?')">🗑️ Delete</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ($total_pages > 1): ?>
        <nav>
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&q=<?php echo $q; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>

</div>

<?php include 'footer.php'; ?>