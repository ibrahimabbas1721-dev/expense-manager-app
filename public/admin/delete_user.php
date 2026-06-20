<?php
require_once(__DIR__ . "/../../includes/auth.php");
requireAdmin();
require_once(__DIR__ . "/../../includes/db.php");

$id = $_GET['id'];

$conn->query("DELETE FROM users WHERE id=$id");

header("Location: users.php");
exit;
?>
