<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /myblog/auth/login.php");
    exit();
}
require_once '../config/db.php';
require_once '../helpers/csrf.php';
verify_csrf();

$id = $_GET['id'] ?? 0;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: /myblog/index.php");
exit();
?>