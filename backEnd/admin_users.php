<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

$message = '';
$error = '';

// Handle delete user
if (isset($_GET['delete'])) {
    $user_id = intval($_GET['delete']);
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
        if ($stmt->execute([$user_id])) {
            $message = 'User deleted successfully!';
        } else {
            $error = 'Error deleting user';
        }
    } catch (Exception $e) {
        $error = 'Database error: ' . htmlspecialchars($e->getMessage());
    }
}

// Fetch all users
$users = [];
try {
    $result = $pdo->query("SELECT id, first_name, last_name, email, phone, gender, created_at FROM users WHERE role != 'admin' ORDER BY created_at DESC");
    $users = $result->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = 'Error fetching users: ' . htmlspecialchars($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Canteen App Admin</title>
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
                <a href="admin_dashboard.php" class="nav-item">📊 Dashboard</a>
                <a href="admin_menu.php" class="nav-item">🍽️ Menu Items</a>
                <a href="admin_orders.php" class="nav-item">📦 Orders</a>
                <a href="admin_users.php" class="nav-item active">👥 Users</a>
                <a href="admin_reports.php" class="nav-item">📈 Reports</a>
                <hr>
                <a href="admin_logout.php" class="nav-item logout">🚪 Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="admin-header">
                <h1>Manage Users</h1>
            </header>

            <?php if (!empty($message)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <!-- Users Table -->
            <section class="table-section">
                <h2>All Users (Total: <?php echo count($users); ?>)</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Gender</th>
                            <th>Joined Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>#<?php echo $user['id']; ?></td>
                                    <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars(ucfirst($user['gender'] ?? 'N/A')); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                    <td>
                                        <a href="#" onclick="viewUser(<?php echo htmlspecialchars(json_encode($user)); ?>)" class="btn-small">👁️ View</a>
                                        <a href="?delete=<?php echo $user['id']; ?>" class="btn-small btn-delete" onclick="return confirm('Delete this user?')">🗑️ Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 20px;">No users yet</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <!-- Modal for viewing user details -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>User Details</h2>
            <div id="userDetails"></div>
        </div>
    </div>

    <script>
        function viewUser(user) {
            const modal = document.getElementById('userModal');
            const details = document.getElementById('userDetails');
            
            let html = `
                <p><strong>User ID:</strong> #${user.id}</p>
                <p><strong>Full Name:</strong> ${user.first_name} ${user.last_name}</p>
                <p><strong>Email:</strong> ${user.email}</p>
                <p><strong>Phone:</strong> ${user.phone || 'N/A'}</p>
                <p><strong>Gender:</strong> ${user.gender ? user.gender.charAt(0).toUpperCase() + user.gender.slice(1) : 'N/A'}</p>
                <p><strong>Joined:</strong> ${new Date(user.created_at).toLocaleDateString()}</p>
            `;
            
            details.innerHTML = html;
            modal.style.display = 'block';
        }

        function closeModal() {
            document.getElementById('userModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('userModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
