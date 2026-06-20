<?php
require_once "../../includes/auth.php";
require_once "../../includes/db.php";
requireLogin();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Report Output</title>
    <link rel="stylesheet" href="../../assets/css/report.css">
</head>
<body>

<?php include '../../includes/user_sideBar.php'; ?>

<div class="print-page-container">

    <!-- <div class="print-btn-container">
        <button class="print-btn" onclick="window.print()">Print Report</button>
    </div> -->

    <div class="report-wrapper">

        <h1 class="report-title">Generated Report</h1>

        <?php
        // Trim GET values
        $month    = isset($_GET['month']) ? trim($_GET['month']) : "";
        $category = isset($_GET['category']) ? trim($_GET['category']) : "";
        $from     = isset($_GET['from']) ? trim($_GET['from']) : "";
        $to       = isset($_GET['to']) ? trim($_GET['to']) : "";

        $sql = "SELECT * FROM transactions";
        $params = [];
        $types = "";
        $filtersApplied = false;

        // MONTHLY REPORT
        if ($month !== "") {
            echo "<div class='summary-box'><strong>Monthly Report:</strong> $month</div>";
            $sql .= " WHERE date >= ? AND date <= ?";
            $startDate = $month . "-01";
            $endDate = date("Y-m-t", strtotime($startDate));
            $params = [$startDate, $endDate];
            $types = "ss";
            $filtersApplied = true;
        }
        // CATEGORY REPORT
        elseif ($category !== "") {
            echo "<div class='summary-box'><strong>Category Report:</strong> $category</div>";
            $sql .= " WHERE category = ?";
            $params = [$category];
            $types = "s";
            $filtersApplied = true;
        }
        // DATE RANGE REPORT
        elseif ($from !== "" && $to !== "") {
            echo "<div class='summary-box'><strong>Date Range:</strong> $from → $to</div>";
            $sql .= " WHERE date >= ? AND date <= ?";
            $params = [$from, $to];
            $types = "ss";
            $filtersApplied = true;
        }

        if (!$filtersApplied) {
            echo "<div class='summary-box error'>No valid filter applied.</div>";
        }

        // Prepare and execute query safely
        if ($filtersApplied) {
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Prepare failed: " . $conn->error);
            }

            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();
            $result = $stmt->get_result();
        }
        ?>

        <h2 class="section-title">Transactions</h2>

        <table class="report-table">
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Category</th>
                <th>Description</th>
                <th>Amount</th>
            </tr>

            <?php
            if ($filtersApplied && $result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>{$row['date']}</td>
                        <td>{$row['type']}</td>
                        <td>{$row['category']}</td>
                        <td>{$row['description']}</td>
                        <td>{$row['amount']}</td>
                    </tr>";
                }
            } elseif ($filtersApplied) {
                echo "<tr><td colspan='5' style='text-align:center;'>No Records Found</td></tr>";
            }
            ?>
        </table>

        <div class="footer-note">
            Expense App Report • Printed on <?= date("Y-m-d H:i") ?>
        </div>

    </div>
</div>

</body>
</html>
