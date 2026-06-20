<?php
require_once(__DIR__ . "/../../includes/auth.php");
require_once(__DIR__ . "/../../includes/db.php");
requireLogin();

if (!isset($_GET['id'])) {
    die("Transaction ID missing.");
}

$id = $_GET['id'];

$sql = "SELECT * FROM transactions WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows == 0) {
    die("Transaction not found.");
}

$transaction = $result->fetch_assoc();
?>

<?php include __DIR__ . '/../../includes/header.php'; ?>
<link rel="stylesheet" href="/expense_app/assets/css/transactions.css">

<div class="layout">

    <!-- Sidebar -->
    <?php include '../../includes/admin_sideBar.php'; ?>

    <!-- Main Content -->
    <main class="content-area">
        <div class="topbar">
            <h3 class="page-title">Transaction Details</h3>
        </div>

        <div class="card transaction-card">
            <p><strong>ID:</strong> <?= $transaction['id'] ?></p>
            <p><strong>Type:</strong> <?= ucfirst($transaction['type']) ?></p>
            <p><strong>Amount:</strong> <?= number_format($transaction['amount'], 2) ?></p>
            <p><strong>Category:</strong> <?= $transaction['category'] ?></p>
            <p><strong>Description:</strong> <?= $transaction['description'] ?></p>
            <p><strong>Date:</strong> <?= $transaction['created_at'] ?></p>
        </div>

        <a href="index.php" class="btn btn-primary">Back to Transactions</a>

    </main>

</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
