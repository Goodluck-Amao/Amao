<?php
session_start();
include 'db.php';

$error = '';
$success = '';

// Handle admin login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Email and password are required';
    } else {
        try {
            // Query to check if user exists and is an admin
            $query = "SELECT id, first_name, password_hash FROM users WHERE email = ? AND role = 'admin'";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Verify password - support both plain text and bcrypt
                $passwordMatch = false;
                
                // Try bcrypt verification
                if (password_verify($password, $user['password_hash'])) {
                    $passwordMatch = true;
                } 
                // Fall back to plain text comparison (for initial setup)
                elseif ($password === $user['password_hash']) {
                    $passwordMatch = true;
                }
                
                if ($passwordMatch) {
                    $_SESSION['admin_id'] = $user['id'];
                    $_SESSION['admin_name'] = $user['first_name'];
                    $_SESSION['admin_logged_in'] = true;
                    header('Location: admin_dashboard.php');
                    exit();
                } else {
                    $error = 'Invalid email or password';
                }
            } else {
                $error = 'Invalid email or password';
            }
        } catch (Exception $e) {
            $error = 'Database error: ' . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Canteen App</title>
    <link rel="stylesheet" href="../frontEnd/css/login.css">
    <style>
        .admin-login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .admin-login-container h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .form-group input:focus {
            outline: none;
            border-color: #2c3e50;
            box-shadow: 0 0 5px rgba(44,62,80,0.3);
        }
        .error {
            color: #e74c3c;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #fadbd8;
            border-radius: 4px;
        }
        .success {
            color: #27ae60;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #d5f4e6;
            border-radius: 4px;
        }
        .submit-btn {
            width: 100%;
            padding: 12px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .submit-btn:hover {
            background-color: #1a252f;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: #3498db;
            text-decoration: none;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="admin-login-container">
        <h1>🔐 Admin Login</h1>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Admin Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="submit-btn">Login</button>
        </form>

        <div class="back-link">
            <a href="../frontEnd/html/index.html">Back to Home</a>
        </div>
    </div>
</body>
</html>
