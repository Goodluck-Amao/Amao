<?php
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../frontEnd/html/index.html');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if (!$name || !$email || !$message) {
    header('Location: ../frontEnd/html/index.html');
    exit;
}

$stmt = $pdo->prepare('INSERT INTO contacts (name, email, message, created_at) VALUES (?, ?, ?, NOW())');
try {
    $stmt->execute([$name, $email, $message]);
    header('Location: ../frontEnd/html/subscribe-success.html');
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo 'Failed to send message: ' . htmlspecialchars($e->getMessage());
}

?>
