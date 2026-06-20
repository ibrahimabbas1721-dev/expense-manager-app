<?php
require_once "../../includes/auth.php";
require_once "../../includes/db.php";
requireLogin();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <link rel="stylesheet" href="../../assets/css/report.css">
</head>
<body>

<?php include '../../includes/admin_sideBar.php'; ?>

<div class="report-container">
    <h1 class="title">Generate Reports</h1>

    <div class="report-grid">

        <!-- Monthly Report -->
        <form action="print.php" method="GET" class="report-card">
            <h3>Monthly Report</h3>
            <label for="month">Select Month:</label>
            <input type="month" name="month" id="month" required>
            <button type="submit" class="btn">Generate</button>
        </form>

        <!-- Category Report -->
        <form action="print.php" method="GET" class="report-card">
            <h3>Category Report</h3>
            <label for="category">Select Category:</label>
            <select name="category" id="category" required>
                <option value="">-- Select --</option>
                <?php
                $cat = $conn->query("SELECT DISTINCT category FROM transactions ORDER BY category ASC");
                if ($cat && $cat->num_rows > 0) {
                    while ($c = $cat->fetch_assoc()) {
                        echo "<option value='".htmlspecialchars($c['category'], ENT_QUOTES)."'>".htmlspecialchars($c['category'], ENT_QUOTES)."</option>";
                    }
                }
                ?>
            </select>
            <button type="submit" class="btn">Generate</button>
        </form>

        <!-- Date Range Report -->
        <form action="print.php" method="GET" class="report-card">
            <h3>Date Range Report</h3>

            <label for="from">From:</label>
            <input type="date" name="from" id="from" required>

            <label for="to">To:</label>
            <input type="date" name="to" id="to" required>

            <button type="submit" class="btn">Generate</button>
        </form>

    </div>
</div>

</body>
</html>
