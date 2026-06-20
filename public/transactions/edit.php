<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';
requireLogin();

if (!isset($_GET['id'])) {
    die("Transaction ID missing.");
}

$id = $_GET['id'];
$error = "";
$success = "";

// Fetch transaction
$sql = "SELECT * FROM transactions WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Transaction not found.");
}

$transaction = $result->fetch_assoc();

// Update transaction
if ($_POST) {
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    $updateSQL = "UPDATE transactions 
                  SET type = ?, amount = ?, category = ?, description = ?
                  WHERE id = ?";
    $updateStmt = $conn->prepare($updateSQL);
    $updateStmt->bind_param("sdssi", $type, $amount, $category, $description, $id);

    if ($updateStmt->execute()) {
        $success = "Transaction updated successfully!";
        // Refresh the transaction data
        $stmt->execute();
        $transaction = $stmt->get_result()->fetch_assoc();
    } else {
        $error = "Failed to update transaction.";
    }
}
?>

<?php include __DIR__ . '/../../includes/header.php'; ?>
<link rel="stylesheet" href="/expense_app/assets/css/transactions.css">

<div class="layout">

    <!-- Sidebar -->
    <?php include '../../includes/admin_sideBar.php'; ?>

    <!-- Main Content -->
    <main class="content-area">
        <div class="topbar">
            <h3 class="page-title">Edit Transaction</h3>
        </div>

        <?php if ($success): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" class="form transaction-form">

            <label>Type</label>
            <select name="type" required>
                <option value="income" <?= $transaction['type'] == 'income' ? 'selected' : '' ?>>Income</option>
                <option value="expense" <?= $transaction['type'] == 'expense' ? 'selected' : '' ?>>Expense</option>
            </select>

            <label>Amount</label>
            <input type="number" step="0.01" name="amount" value="<?= $transaction['amount'] ?>" required>

            <label>Category</label>
            <input type="text" name="category" value="<?= $transaction['category'] ?>">

            <label>Description</label>
            <textarea name="description"><?= $transaction['description'] ?></textarea>

            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Update</button>
                <a href="view.php?id=<?= $transaction['id'] ?>" class="btn btn-primary">Cancel</a>
            </div>
        </form>
    </main>

</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
