<?php
require_once "../../includes/auth.php"; // user must be logged in
require_once "../../includes/db.php";      // connect to 

if ($_SESSION['role'] !== 'user') {
    header("Location: ../dashboard.php");
    exit();
}

// Fetch total income
$sqlIncome = "SELECT SUM(amount) as total_income FROM transactions WHERE type='income'";
$resIncome = $conn->query($sqlIncome);
$totalIncome = $resIncome->fetch_assoc()['total_income'];
if(!$totalIncome) $totalIncome = 0;

// Fetch total expense
$sqlExpense = "SELECT SUM(amount) as total_expense FROM transactions WHERE type='expense'";
$resExpense = $conn->query($sqlExpense);
$totalExpense = $resExpense->fetch_assoc()['total_expense'];
if(!$totalExpense) $totalExpense = 0;

// Calculate profit
$profit = $totalIncome - $totalExpense;

/* ------------------------------
   CATEGORY-WISE EXPENSE SUMMARY
-------------------------------*/
$sqlCat = "SELECT category, SUM(amount) AS total 
           FROM transactions 
           WHERE type='expense' 
           GROUP BY category";
$resCat = $conn->query($sqlCat);

$categories = [];
$catTotals = [];

while ($row = $resCat->fetch_assoc()) {
    $categories[] = $row['category'];
    $catTotals[] = $row['total'];
}

/* ------------------------------
   MONTHLY INCOME & EXPENSE TREND
-------------------------------*/
$sqlMonthly = "
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m') AS month,
        SUM(CASE WHEN type='income' THEN amount ELSE 0 END) AS income,
        SUM(CASE WHEN type='expense' THEN amount ELSE 0 END) AS expense
    FROM transactions
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY month ASC
";

$resMonthly = $conn->query($sqlMonthly);

$months = [];
$monthlyIncome = [];
$monthlyExpense = [];

while ($row = $resMonthly->fetch_assoc()) {
    $months[] = $row['month'];
    $monthlyIncome[] = $row['income'];
    $monthlyExpense[] = $row['expense'];
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php
include '../../includes/user_sideBar.php';
?>

<div class="main-content">
    <div class="topbar">
        <h3 class="page-title">Welcome, <?php echo $_SESSION['role']; ?></h3>
    </div>

    <div class="content">
        <h2 class="dash">Dashboard Overview</h2>

        <div class="cards">
            <div class="card">
                <h3>Total Income</h3>
                <p><?php echo number_format($totalIncome, 2); ?></p>
            </div>

            <div class="card">
                <h3>Total Expenses</h3>
                <p><?php echo number_format($totalExpense, 2); ?></p>
            </div>

            <div class="card">
                <h3>Profit</h3>
                <p><?php echo number_format($profit, 2); ?></p>
            </div>
        </div>
    </div>
     <div class="charts-row">

    <div class="chart-card">
        <h3>Income vs Expense</h3>
        <canvas id="incomeExpenseChart"></canvas>
    </div>

    <div class="chart-card">
        <h3>Category-wise Expense Breakdown</h3>
        <canvas id="categoryChart"></canvas>
    </div>

    <div class="chart-card">
        <h3>Monthly Income vs Expense Trend</h3>
        <canvas id="monthlyChart"></canvas>
    </div>

</div>

    
</div>
</div>

</body>
<script>
// Wait until page loads fully
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('incomeExpenseChart').getContext('2d');

    // Create gradient background
    const gradientIncome = ctx.createLinearGradient(0, 0, 0, 400);
    gradientIncome.addColorStop(0, 'rgba(76, 175, 80, 0.8)');
    gradientIncome.addColorStop(1, 'rgba(76, 175, 80, 0.2)');

    const gradientExpense = ctx.createLinearGradient(0, 0, 0, 400);
    gradientExpense.addColorStop(0, 'rgba(244, 67, 54, 0.8)');
    gradientExpense.addColorStop(1, 'rgba(244, 67, 54, 0.2)');

    const gradientProfit = ctx.createLinearGradient(0, 0, 0, 400);
    gradientProfit.addColorStop(0, 'rgba(0, 123, 255, 0.8)');
    gradientProfit.addColorStop(1, 'rgba(0, 123, 255, 0.2)');

    /* ----------------------------------------
    CATEGORY-WISE EXPENSE PIE CHART
-----------------------------------------*/
var ctx2 = document.getElementById('categoryChart').getContext('2d');
var categoryChart = new Chart(ctx2, {
    type: 'pie',
    data: {
        labels: <?= json_encode($categories) ?>,
        datasets: [{
            data: <?= json_encode($catTotals) ?>,
            backgroundColor: [
                '#ff6384', '#36a2eb', '#ffce56',
                '#4caf50', '#ff9800', '#9c27b0'
            ]
        }]
    },
    options: {
        responsive: true
    }
});

/* ----------------------------------------
    MONTHLY INCOME VS EXPENSE LINE CHART
-----------------------------------------*/
var ctx3 = document.getElementById('monthlyChart').getContext('2d');
var monthlyChart = new Chart(ctx3, {
    type: 'line',
    data: {
        labels: <?= json_encode($months) ?>,
        datasets: [
            {
                label: 'Income',
                data: <?= json_encode($monthlyIncome) ?>,
                borderColor: '#4CAF50',
                fill: false
            },
            {
                label: 'Expense',
                data: <?= json_encode($monthlyExpense) ?>,
                borderColor: '#f44336',
                fill: false
            }
        ]
    },
    options: {
        responsive: true,
        tension: 0.3,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});


    // Load chart
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Income', 'Expense', 'Profit'],
            datasets: [{
                label: 'Amount',
                data: [<?= $totalIncome ?>, <?= $totalExpense ?>, <?= $profit ?>],
                backgroundColor: [gradientIncome, gradientExpense, gradientProfit],
                borderRadius: 12,
                borderWidth: 2,
                borderColor: ['#4CAF50', '#f44336', '#007bff'],
                hoverBackgroundColor: ['#4CAF50', '#f44336', '#007bff'],
                hoverBorderWidth: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Income • Expenses • Profit Overview',
                    color: '#333',
                    font: {
                        size: 20,
                        weight: 'bold'
                    },
                    padding: 20
                },
                tooltip: {
                    backgroundColor: '#333',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false,
                }
            },

            scales: {
                x: {
                    ticks: {
                        color: '#444',
                        font: { size: 14 }
                    },
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#666',
                        font: { size: 13 }
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.05)',
                        borderDash: [5, 5]
                    }
                }
            }
        }
    });
});
</script>

</html>
