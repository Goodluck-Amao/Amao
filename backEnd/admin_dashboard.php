<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

// Fetch dashboard statistics
$total_users = 0;
$total_orders = 0;
$pending_orders = 0;
$total_revenue = 0;
$recent_orders = [];

try {
    // Get total users
    $result = $pdo->query("SELECT COUNT(*) as count FROM users WHERE role != 'admin'");
    $total_users = $result->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

    // Get total orders
    $result = $pdo->query("SELECT COUNT(*) as count FROM orders");
    $total_orders = $result->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

    // Get pending orders
    $result = $pdo->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'");
    $pending_orders = $result->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

    // Get total revenue
    $result = $pdo->query("SELECT SUM(total) as revenue FROM orders");
    $total_revenue = $result->fetch(PDO::FETCH_ASSOC)['revenue'] ?? 0;

    // Get recent orders
    $result = $pdo->query("SELECT o.id, u.first_name, u.last_name, o.total, o.status, o.created_at FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 5");
    $recent_orders = $result->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Error fetching data: " . htmlspecialchars($e->getMessage());
}

$admin_name = $_SESSION['admin_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Canteen App</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="logo">
                <h2>🍽️ Admin Panel</h2>
            </div>
            <nav class="nav-menu">
                <a href="admin_dashboard.php" class="nav-item active">📊 Dashboard</a>
                <a href="admin_menu.php" class="nav-item">🍽️ Menu Items</a>
                <a href="admin_orders.php" class="nav-item">📦 Orders</a>
                <a href="admin_users.php" class="nav-item">👥 Users</a>
                <a href="admin_reports.php" class="nav-item">📈 Reports</a>
                <hr>
                <a href="admin_logout.php" class="nav-item logout">🚪 Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="admin-header">
                <h1>Dashboard</h1>
                <div class="header-info">
                    <span>Welcome, <strong><?php echo htmlspecialchars($admin_name); ?></strong></span>
                    <span class="current-date"><?php echo date('D, M d, Y'); ?></span>
                </div>
            </header>

            <!-- Statistics Cards -->
            <section class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-content">
                        <h3>Total Users</h3>
                        <p class="stat-number"><?php echo $total_users; ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">📦</div>
                    <div class="stat-content">
                        <h3>Total Orders</h3>
                        <p class="stat-number"><?php echo $total_orders; ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-content">
                        <h3>Pending Orders</h3>
                        <p class="stat-number" style="color: #e74c3c;"><?php echo $pending_orders; ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-content">
                        <h3>Total Revenue</h3>
                        <p class="stat-number">₦<?php echo number_format($total_revenue, 2); ?></p>
                    </div>
                </div>
            </section>

            <!-- Recent Orders Table -->
            <section class="recent-orders">
                <h2>Recent Orders</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recent_orders)): ?>
                            <?php foreach ($recent_orders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
                                    <td>₦<?php echo number_format($order['total'], 2); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                    <td><a href="admin_orders.php?view=<?php echo $order['id']; ?>" class="view-btn">View</a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 20px;">No orders yet</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>
