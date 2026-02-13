<?php
require_once __DIR__ . '/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../frontEnd/html/register.php');
    exit;
}

$first = trim($_POST['first_name'] ?? '');
$last = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';
$gender = $_POST['gender'] ?? '';

$errors = [];
if (!$first) $errors[] = 'First name is required.';
if (!$last) $errors[] = 'Last name is required.';
if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
if (!$password) $errors[] = 'Password is required.';
if ($password !== $password_confirm) $errors[] = 'Passwords do not match.';

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'messages' => $errors];
    $_SESSION['old'] = ['first_name' => $first, 'last_name' => $last, 'email' => $email, 'phone' => $phone, 'gender' => $gender];
    header('Location: ../frontEnd/html/register.php');
    exit;
}

// Check duplicate email
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
if ($stmt->fetch()) {
    $_SESSION['flash'] = ['type' => 'error', 'messages' => ['Email already registered.']];
    $_SESSION['old'] = ['first_name' => $first, 'last_name' => $last, 'email' => $email, 'phone' => $phone, 'gender' => $gender];
    header('Location: ../frontEnd/html/register.php');
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare('INSERT INTO users (first_name, last_name, email, phone, password_hash, gender, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())');
try {
    $stmt->execute([$first, $last, $email, $phone, $hash, $gender]);
    $_SESSION['flash'] = ['type' => 'success', 'messages' => ['Registration successful. You can log in now.']];
    header('Location: ../frontEnd/html/login.php');
    exit;
} catch (Exception $e) {
    $_SESSION['flash'] = ['type' => 'error', 'messages' => ['Registration failed. Please try again.']];
    header('Location: ../frontEnd/html/register.php');
    exit;
}

?>
