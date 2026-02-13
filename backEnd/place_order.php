<?php
require_once __DIR__ . '/db.php';
session_start();

if (empty($_SESSION['user_id'])) {
    $_SESSION['flash'] = ['type'=>'error','messages'=>['You must be logged in to place an order.']];
    header('Location: ../frontEnd/html/login.php');
    exit;
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    $_SESSION['flash'] = ['type'=>'error','messages'=>['Your cart is empty.']];
    header('Location: ../frontEnd/html/order.php');
    exit;
}

$ids = array_keys($cart);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT id, name, price FROM menu_items WHERE id IN ($placeholders)");
$stmt->execute($ids);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$order_items = [];
$total = 0.0;
foreach ($items as $it) {
    $id = $it['id'];
    $qty = $cart[$id] ?? 0;
    if ($qty <= 0) continue;
    $subtotal = $it['price'] * $qty;
    $order_items[] = ['id'=>$id,'name'=>$it['name'],'price'=>$it['price'],'qty'=>$qty,'subtotal'=>$subtotal];
    $total += $subtotal;
}

if (empty($order_items)) {
    $_SESSION['flash'] = ['type'=>'error','messages'=>['No valid items in cart.']];
    header('Location: ../frontEnd/html/order.php');
    exit;
}

try {
    $ins = $pdo->prepare('INSERT INTO orders (user_id, items, total, status, created_at) VALUES (?, ?, ?, ?, NOW())');
    $ins->execute([$_SESSION['user_id'], json_encode($order_items), $total, 'pending']);
    unset($_SESSION['cart']);
    $_SESSION['flash'] = ['type'=>'success','messages'=>['Order placed successfully.']];
    header('Location: ../frontEnd/html/order.php');
    exit;
} catch (Exception $e) {
    $_SESSION['flash'] = ['type'=>'error','messages'=>['Failed to place order.']];
    header('Location: ../frontEnd/html/order.php');
    exit;
}

?>
