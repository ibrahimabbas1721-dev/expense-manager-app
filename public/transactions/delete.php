<?php
require_once '../../includes/db.php';
require_once '../../includes/auth.php';
requireLogin();

if (!isset($_GET['id'])) {
    die("Transaction ID missing.");
}

$id = $_GET['id'];

$sql = "DELETE FROM transactions WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

$stmt->execute();

header("Location: index.php?deleted=1");
exit;
