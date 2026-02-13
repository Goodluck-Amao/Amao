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
$menu_items = [];

// Handle add/edit menu item
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $category = trim($_POST['category'] ?? '');
    $image = $_POST['image'] ?? '';

    if (empty($name) || $price <= 0 || empty($category)) {
        $error = 'Name, price, and category are required';
    } else {
        try {
            if ($action == 'add') {
                $stmt = $pdo->prepare("INSERT INTO menu_items (name, description, price, category, image) VALUES (?, ?, ?, ?, ?)");
                if ($stmt->execute([$name, $description, $price, $category, $image])) {
                    $message = 'Menu item added successfully!';
                } else {
                    $error = 'Error adding menu item';
                }
            } elseif ($action == 'edit') {
                $id = intval($_POST['id'] ?? 0);
                if ($id > 0) {
                    $stmt = $pdo->prepare("UPDATE menu_items SET name=?, description=?, price=?, category=?, image=? WHERE id=?");
                    if ($stmt->execute([$name, $description, $price, $category, $image, $id])) {
                        $message = 'Menu item updated successfully!';
                    } else {
                        $error = 'Error updating menu item';
                    }
                }
            }
        } catch (Exception $e) {
            $error = 'Database error: ' . htmlspecialchars($e->getMessage());
        }
    }
}

// Handle delete menu item
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    try {
        $stmt = $pdo->prepare("DELETE FROM menu_items WHERE id = ?");
        if ($stmt->execute([$id])) {
            $message = 'Menu item deleted successfully!';
        } else {
            $error = 'Error deleting menu item';
        }
    } catch (Exception $e) {
        $error = 'Database error: ' . htmlspecialchars($e->getMessage());
    }
}

// Fetch all menu items
try {
    $result = $pdo->query("SELECT * FROM menu_items ORDER BY created_at DESC");
    $menu_items = $result->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = 'Error fetching menu items: ' . htmlspecialchars($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu - Canteen App Admin</title>
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
                <a href="admin_menu.php" class="nav-item active">🍽️ Menu Items</a>
                <a href="admin_orders.php" class="nav-item">📦 Orders</a>
                <a href="admin_users.php" class="nav-item">👥 Users</a>
                <a href="admin_reports.php" class="nav-item">📈 Reports</a>
                <hr>
                <a href="admin_logout.php" class="nav-item logout">🚪 Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="admin-header">
                <h1>Manage Menu Items</h1>
            </header>

            <?php if (!empty($message)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <!-- Add/Edit Form -->
            <section class="form-section">
                <h2>Add New Menu Item</h2>
                <form method="POST" action="" class="admin-form">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="id" id="item-id">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Item Name *</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="price">Price (₦) *</label>
                            <input type="number" id="price" name="price" step="0.01" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="category">Category *</label>
                            <select id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="breakfast">Breakfast</option>
                                <option value="lunch">Lunch</option>
                                <option value="dinner">Dinner</option>
                                <option value="snack">Snack</option>
                                <option value="beverage">Beverage</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="image">Image URL</label>
                            <input type="text" id="image" name="image" placeholder="frontEnd/image/image.jpg">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Add / Update Item</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </form>
            </section>

            <!-- Menu Items Table -->
            <section class="table-section">
                <h2>All Menu Items</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($menu_items)): ?>
                            <?php foreach ($menu_items as $item): ?>
                                <tr>
                                    <td>#<?php echo $item['id']; ?></td>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td><span class="badge"><?php echo ucfirst($item['category']); ?></span></td>
                                    <td>₦<?php echo number_format($item['price'], 2); ?></td>
                                    <td><?php echo htmlspecialchars(substr($item['description'] ?? '', 0, 50)); ?></td>
                                    <td>
                                        <button class="btn-small btn-edit" onclick="editItem(<?php echo htmlspecialchars(json_encode($item)); ?>)">✏️ Edit</button>
                                        <a href="?delete=<?php echo $item['id']; ?>" class="btn-small btn-delete" onclick="return confirm('Are you sure?')">🗑️ Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 20px;">No menu items yet</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <script>
        function editItem(item) {
            document.querySelector('input[name="action"]').value = 'edit';
            document.getElementById('item-id').value = item.id;
            document.getElementById('name').value = item.name;
            document.getElementById('price').value = item.price;
            document.getElementById('category').value = item.category;
            document.getElementById('image').value = item.image || '';
            document.getElementById('description').value = item.description || '';
            document.querySelector('h2').textContent = 'Edit Menu Item';
            window.scrollTo(0, 0);
        }
    </script>
</body>
</html>
