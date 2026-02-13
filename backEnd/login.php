<?php
require_once __DIR__ . '/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../frontEnd/html/login.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
    $_SESSION['flash'] = ['type' => 'error', 'messages' => ['Username and password are required.']];
    header('Location: ../frontEnd/html/login.php');
    exit;
}

$stmt = $pdo->prepare('SELECT id, first_name, password_hash FROM users WHERE email = ? OR phone = ? OR CONCAT(first_name, " ", last_name) = ? LIMIT 1');
$stmt->execute([$username, $username, $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password_hash'])) {
    // successful login
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['first_name'];
    header('Location: ../frontEnd/html/order.php');
    exit;
} else {
    $_SESSION['flash'] = ['type' => 'error', 'messages' => ['Invalid username or password.']];
    header('Location: ../frontEnd/html/login.php');
    exit;
}

?>
