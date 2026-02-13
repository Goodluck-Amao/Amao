<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

// Fetch report data
$monthly_revenue = [];
$top_items = [];
$order_status_stats = [];
$user_stats = [];

try {
    // Monthly Revenue
    $result = $pdo->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total) as revenue, COUNT(*) as order_count FROM orders GROUP BY DATE_FORMAT(created_at, '%Y-%m') ORDER BY month DESC LIMIT 12");
    $monthly_revenue = $result->fetchAll(PDO::FETCH_ASSOC);

    // Top Items
    $result = $pdo->query("SELECT name, category, COUNT(*) as times_ordered, SUM(price) as revenue FROM menu_items GROUP BY id ORDER BY times_ordered DESC LIMIT 10");
    $top_items = $result->fetchAll(PDO::FETCH_ASSOC);

    // Order Status Stats
    $result = $pdo->query("SELECT status, COUNT(*) as count FROM orders GROUP BY status");
    $order_status_stats = $result->fetchAll(PDO::FETCH_ASSOC);

    // User Statistics
    $result = $pdo->query("SELECT COUNT(*) as total_users, SUM(CASE WHEN gender = 'male' THEN 1 ELSE 0 END) as male_users, SUM(CASE WHEN gender = 'female' THEN 1 ELSE 0 END) as female_users FROM users WHERE role != 'admin'");
    $user_stats = $result->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = 'Error fetching data: ' . htmlspecialchars($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Canteen App Admin</title>
    <link rel="stylesheet" href="admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="logo">
                <h2>🍽️ Admin Panel</h2>
            </div>
            <nav class="nav-menu">
                <a href="admin_dashboard.php" class="nav-item">📊 Dashboard</a>
                <a href="admin_menu.php" class="nav-item">🍽️ Menu Items</a>
                <a href="admin_orders.php" class="nav-item">📦 Orders</a>
                <a href="admin_users.php" class="nav-item">👥 Users</a>
                <a href="admin_reports.php" class="nav-item active">📈 Reports</a>
                <hr>
                <a href="admin_logout.php" class="nav-item logout">🚪 Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="admin-header">
                <h1>Reports & Analytics</h1>
            </header>

            <!-- User Statistics -->
            <section class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-content">
                        <h3>Total Users</h3>
                        <p class="stat-number"><?php echo $user_stats['total_users'] ?? 0; ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">👨</div>
                    <div class="stat-content">
                        <h3>Male Users</h3>
                        <p class="stat-number"><?php echo $user_stats['male_users'] ?? 0; ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">👩</div>
                    <div class="stat-content">
                        <h3>Female Users</h3>
                        <p class="stat-number"><?php echo $user_stats['female_users'] ?? 0; ?></p>
                    </div>
                </div>
            </section>

            <!-- Charts Section -->
            <section class="charts-grid">
                <div class="chart-container">
                    <h3>Order Status Distribution</h3>
                    <canvas id="statusChart"></canvas>
                </div>

                <div class="chart-container">
                    <h3>Monthly Revenue</h3>
                    <canvas id="revenueChart"></canvas>
                </div>
            </section>

            <!-- Monthly Revenue Table -->
            <section class="table-section">
                <h2>Monthly Revenue Report</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Orders</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($monthly_revenue)): ?>
                            <?php foreach ($monthly_revenue as $month): ?>
                                <tr>
                                    <td><?php echo $month['month']; ?></td>
                                    <td><?php echo $month['order_count']; ?></td>
                                    <td>₦<?php echo number_format($month['revenue'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 20px;">No data available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>

            <!-- Top Items Table -->
            <section class="table-section">
                <h2>Top Menu Items</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Times Ordered</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($top_items)): ?>
                            <?php foreach ($top_items as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td><span class="badge"><?php echo ucfirst($item['category']); ?></span></td>
                                    <td><?php echo $item['times_ordered']; ?></td>
                                    <td>₦<?php echo number_format($item['revenue'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 20px;">No data available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <script>
        // Order Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: [<?php echo implode(',', array_map(fn($s) => "'{$s['status']}'", $order_status_stats)); ?>],
                datasets: [{
                    data: [<?php echo implode(',', array_map(fn($s) => $s['count'], $order_status_stats)); ?>],
                    backgroundColor: ['#3498db', '#e74c3c', '#f39c12', '#27ae60', '#95a5a6']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // Monthly Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: [<?php echo implode(',', array_map(fn($r) => "'{$r['month']}'", $monthly_revenue)); ?>],
                datasets: [{
                    label: 'Revenue (₦)',
                    data: [<?php echo implode(',', array_map(fn($r) => $r['revenue'], $monthly_revenue)); ?>],
                    borderColor: '#27ae60',
                    backgroundColor: 'rgba(39, 174, 96, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₦' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
