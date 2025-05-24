<?php
// Database Connection
include '../includes/connection.php';

// Fetch Daily, Weekly, Monthly Sales
$salesData = [];
$query = "SELECT DATE(`DATE`) AS sales_date, SUM(CAST(`GRANDTOTAL` AS DECIMAL(10,2))) AS total_sales 
          FROM `transaction` WHERE `STATUS` = 'Active' 
          GROUP BY sales_date ORDER BY sales_date ASC";
$result = $db->query($query);
while ($row = $result->fetch_assoc()) {
    $salesData[] = $row;
}

// Fetch Top Selling Products
$topProducts = [];
$query = "SELECT p.NAME AS product_name, SUM(td.QTY) AS total_sold 
          FROM transaction_details td 
          JOIN product p ON td.PRODUCT_ID = p.PRODUCT_ID 
          GROUP BY p.PRODUCT_ID ORDER BY total_sold DESC LIMIT 10";
$result = $db->query($query);
while ($row = $result->fetch_assoc()) {
    $topProducts[] = $row;
}

// Fetch Sales per Branch
$salesByBranch = [];
$query = "SELECT b.BRANCH_NAME, SUM(CAST(t.GRANDTOTAL AS DECIMAL(10,2))) AS total_sales 
          FROM transaction t 
          JOIN branches b ON t.TRANS_ID = b.BRANCH_ID 
          WHERE t.STATUS = 'Active' 
          GROUP BY b.BRANCH_ID ORDER BY total_sales DESC";
$result = $db->query($query);
while ($row = $result->fetch_assoc()) {
    $salesByBranch[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sales Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">MJC Hardware Sales Statistics</h2>

        <div class="row mt-4">
            <!-- Sales Over Time -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Daily Sales Trend</div>
                    <div class="card-body">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top Selling Products -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Top Selling Products</div>
                    <div class="card-body">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Sales per Branch -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Sales per Branch</div>
                    <div class="card-body">
                        <canvas id="branchSalesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sales Trend Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: [<?php foreach ($salesData as $sale) echo '"' . $sale['sales_date'] . '",'; ?>],
                datasets: [{
                    label: 'Total Sales',
                    data: [<?php foreach ($salesData as $sale) echo $sale['total_sales'] . ','; ?>],
                    borderColor: 'blue',
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    fill: true
                }]
            }
        });

        // Top Selling Products Chart
        const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
        new Chart(topProductsCtx, {
            type: 'bar',
            data: {
                labels: [<?php foreach ($topProducts as $product) echo '"' . $product['product_name'] . '",'; ?>],
                datasets: [{
                    label: 'Units Sold',
                    data: [<?php foreach ($topProducts as $product) echo $product['total_sold'] . ','; ?>],
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'red',
                    borderWidth: 1
                }]
            }
        });

        // Sales per Branch Chart
        const branchSalesCtx = document.getElementById('branchSalesChart').getContext('2d');
        new Chart(branchSalesCtx, {
            type: 'bar',
            data: {
                labels: [<?php foreach ($salesByBranch as $branch) echo '"' . $branch['BRANCH_NAME'] . '",'; ?>],
                datasets: [{
                    label: 'Total Sales',
                    data: [<?php foreach ($salesByBranch as $branch) echo $branch['total_sales'] . ','; ?>],
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'blue',
                    borderWidth: 1
                }]
            }
        });
    </script>

</body>
</html>
