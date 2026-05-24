<?php
session_start();
session_destroy();
header("Location: /myblog/auth/login.php");
exit();
?>