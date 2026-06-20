<?php
require_once(__DIR__ . "/../../includes/auth.php");
require_once(__DIR__ . "/../../includes/db.php");
requireLogin();

/* -----------------------------------------
   BUILD FILTER QUERY
------------------------------------------ */

$sql = "SELECT * FROM transactions WHERE 1=1";
$params = [];
$types = "";

// Date From
if (!empty($_GET['from'])) {
    $sql .= " AND created_at >= ?";
    $params[] = $_GET['from'];
    $types .= "s";
}

// Date To
if (!empty($_GET['to'])) {
    $sql .= " AND created_at <= ?";
    $params[] = $_GET['to'];
    $types .= "s";
}

// Category
if (!empty($_GET['category'])) {
    $sql .= " AND category = ?";
    $params[] = $_GET['category'];
    $types .= "s";
}

// Min Amount
if (!empty($_GET['min'])) {
    $sql .= " AND amount >= ?";
    $params[] = $_GET['min'];
    $types .= "d";
}

// Max Amount
if (!empty($_GET['max'])) {
    $sql .= " AND amount <= ?";
    $params[] = $_GET['max'];
    $types .= "d";
}

// Type (income/expense)
if (!empty($_GET['type'])) {
    $sql .= " AND type = ?";
    $params[] = $_GET['type'];
    $types .= "s";
}

$sql .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

?>

<?php include __DIR__ . '/../../includes/header.php'; ?>
<link rel="stylesheet" href="/expense_app/assets/css/transactions.css">

<div class="layout">

    <!-- Sidebar -->
    <?php
include '../../includes/user_sideBar.php';
?>

    <!-- Main Content -->
    <main class="content-area">

        <div class="topbar">
            <h3 class="page-title">All Transactions</h3>
        </div>

        <!-- FILTERS -->
        <div class="filter-box">
            <form method="GET" class="filter-form">

                <div class="filter-item">
                    <label>Date From:</label>
                    <input type="date" name="from" value="<?= $_GET['from'] ?? '' ?>">
                </div>

                <div class="filter-item">
                    <label>Date To:</label>
                    <input type="date" name="to" value="<?= $_GET['to'] ?? '' ?>">
                </div>

                <div class="filter-item">
                    <label>Category:</label>
                    <select name="category">
                        <option value="">All</option>

                        <?php
                        $cat = $conn->query("SELECT DISTINCT category FROM transactions");
                        while ($row = $cat->fetch_assoc()) {
                            $selected = ($_GET['category'] ?? '') == $row['category'] ? "selected" : "";
                            echo "<option $selected value='{$row['category']}'>{$row['category']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="filter-item">
                    <label>Amount (Min):</label>
                    <input type="number" name="min" value="<?= $_GET['min'] ?? '' ?>">
                </div>

                <div class="filter-item">
                    <label>Amount (Max):</label>
                    <input type="number" name="max" value="<?= $_GET['max'] ?? '' ?>">
                </div>

                <div class="filter-item">
                    <label>Type:</label>
                    <select name="type">
                        <option value="">All</option>
                        <option value="income" <?= (($_GET['type'] ?? '') === 'income') ? "selected" : ""; ?>>Income</option>
                        <option value="expense" <?= (($_GET['type'] ?? '') === 'expense') ? "selected" : ""; ?>>Expense</option>
                    </select>
                </div>

                <button type="submit" class="btn">Apply Filters</button>
                <a href="index.php" class="btn clear">Clear</a>

            </form>
        </div>

        <!-- TABLE -->
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Category</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= ucfirst($row['type']) ?></td>
                            <td><?= number_format($row['amount'], 2) ?></td>
                            <td><?= $row['category'] ?></td>
                            <td><?= $row['created_at'] ?></td>

                            <td class="actions">
                                <a href="view.php?id=<?= $row['id'] ?>" class="btn btn-view">View</a>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </main>

</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
