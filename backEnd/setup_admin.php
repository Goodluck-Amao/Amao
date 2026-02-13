<?php
/**
 * Admin Panel Setup Script
 * This file checks and initializes the admin panel
 * Run this once in your browser: http://localhost/CANTEEN%20APP/backEnd/setup_admin.php
 */

include 'db.php';

$output = '';
$errors = [];
$success = [];

// 1. Check if role column exists
try {
    $result = $pdo->query("DESCRIBE users");
    $columns = $result->fetchAll(PDO::FETCH_ASSOC);
    $hasRole = false;
    foreach ($columns as $col) {
        if ($col['Field'] === 'role') {
            $hasRole = true;
            break;
        }
    }
    
    if (!$hasRole) {
        // Add role column
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `role` VARCHAR(20) DEFAULT 'user'");
        $success[] = "✅ Added 'role' column to users table";
    } else {
        $success[] = "✅ 'role' column already exists";
    }
} catch (Exception $e) {
    $errors[] = "❌ Error checking role column: " . $e->getMessage();
}

// 2. Check if admin user exists
try {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND role = 'admin'");
    $stmt->execute(['admin@canteen.com']);
    
    if ($stmt->rowCount() === 0) {
        // Create admin user
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, password_hash, role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute(['Admin', 'User', 'admin@canteen.com', '+2341234567890', 'admin123', 'admin']);
        $success[] = "✅ Created admin user (email: admin@canteen.com, password: admin123)";
    } else {
        $success[] = "✅ Admin user already exists";
    }
} catch (Exception $e) {
    $errors[] = "❌ Error creating admin user: " . $e->getMessage();
}

// 3. Update existing admin to have role
try {
    $pdo->exec("UPDATE users SET role = 'admin' WHERE email = 'admin@canteen.com'");
    $success[] = "✅ Ensured admin@canteen.com has admin role";
} catch (Exception $e) {
    $errors[] = "❌ Error updating admin role: " . $e->getMessage();
}

// 4. Add indexes if they don't exist
try {
    $pdo->exec("ALTER TABLE users ADD INDEX IF NOT EXISTS idx_email (email)");
    $pdo->exec("ALTER TABLE users ADD INDEX IF NOT EXISTS idx_role (role)");
    $pdo->exec("ALTER TABLE orders ADD INDEX IF NOT EXISTS idx_user_id (user_id)");
    $pdo->exec("ALTER TABLE orders ADD INDEX IF NOT EXISTS idx_status (status)");
    $success[] = "✅ Database indexes added/verified";
} catch (Exception $e) {
    // Silently fail if indexes already exist
    // $errors[] = "Note: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel Setup</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 30px;
            background-color: #f5f7fa;
            max-width: 600px;
            margin: 0 auto;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 30px;
            text-align: center;
        }
        .success-message {
            background-color: #d5f4e6;
            color: #27ae60;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #27ae60;
        }
        .error-message {
            background-color: #fadbd8;
            color: #e74c3c;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #e74c3c;
        }
        .info-box {
            background-color: #e8f4f8;
            color: #2c3e50;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #3498db;
        }
        .button-container {
            text-align: center;
            margin-top: 30px;
        }
        .btn {
            padding: 12px 30px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin: 0 10px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .btn-secondary {
            background-color: #95a5a6;
        }
        .btn-secondary:hover {
            background-color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Admin Panel Setup</h1>
        
        <?php if (!empty($success)): ?>
            <h2>✅ Setup Successful</h2>
            <?php foreach ($success as $msg): ?>
                <div class="success-message"><?php echo $msg; ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <h2>⚠️ Issues Found</h2>
            <?php foreach ($errors as $msg): ?>
                <div class="error-message"><?php echo $msg; ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <div class="info-box">
            <h3>📋 Default Admin Credentials</h3>
            <p><strong>Email:</strong> admin@canteen.com</p>
            <p><strong>Password:</strong> admin123</p>
            <p style="margin-top: 15px; color: #e74c3c;">
                <strong>⚠️ Important:</strong> Change these credentials immediately after first login!
            </p>
        </div>
        
        <div class="button-container">
            <a href="admin_login.php" class="btn">🔐 Go to Admin Login</a>
            <a href="../frontEnd/html/index.html" class="btn btn-secondary">🏠 Back to Home</a>
        </div>
    </div>
</body>
</html>
