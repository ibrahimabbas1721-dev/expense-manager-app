<?php
require_once(__DIR__ . "/../../includes/auth.php");
require_once(__DIR__ . "/../../includes/db.php");
requireLogin();

$error = "";
$success = "";

if ($_POST) {
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $date = $_POST['date']; // Transaction date

    // Make sure date is provided
    if (empty($date)) {
        $error = "Please select a date for the transaction.";
    } else {
        // Insert transaction with both date and created_at
        $sql = "INSERT INTO transactions (type, amount, category, description, date, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsss", $type, $amount, $category, $description, $date);

        if ($stmt->execute()) {
            $success = "Transaction added successfully!";
        } else {
            $error = "Failed to add transaction. Error: " . $stmt->error;
        }
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
            <h3 class="page-title">Add New Transaction</h3>
        </div>

        <?php if ($success): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form action="" method="POST" class="form">

            <label>Type</label>
            <select name="type" required>
                <option value="income">Income</option>
                <option value="expense">Expense</option>
            </select>

            <label>Amount</label>
            <input type="number" step="0.01" name="amount" required>

            <label>Category</label>
            <input type="text" name="category">

            <label>Description</label>
            <textarea name="description"></textarea>

            <label>Date</label>
            <input type="date" name="date" required value="<?= date('Y-m-d') ?>">

            <button type="submit" class="btn btn-primary">Add Transaction</button>
        </form>

    </main>

</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
