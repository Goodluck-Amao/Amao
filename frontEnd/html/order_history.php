<?php
session_start();
require_once __DIR__ . '/../../backEnd/db.php';

if (empty($_SESSION['user_id'])) {
    $_SESSION['flash'] = ['type'=>'error','messages'=>['Please log in to view your orders.']];
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare('SELECT id, items, total, status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Your Orders - SilverGold Canteen</title>
  <link rel="stylesheet" href="../css/style.css" />
  <style>
    .orders-wrap{max-width:900px;margin:28px auto;padding:18px}
    .order-card{background:#fff;border-radius:10px;padding:12px;margin-bottom:12px;box-shadow:0 6px 18px rgba(0,0,0,0.04)}
    .order-meta{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px}
    .order-items{margin-left:12px}
    .order-items li{margin-bottom:6px}
  </style>
</head>
<body>
  <div class="orders-wrap">
    <h2>Your Orders</h2>
    <?php if (empty($orders)): ?>
      <p>You have no orders yet. <a href="order.php">Browse menu</a></p>
    <?php else: foreach ($orders as $o): ?>
      <div class="order-card">
        <div class="order-meta">
          <div>
            <strong>Order #<?= htmlspecialchars($o['id']) ?></strong><br>
            <small><?= htmlspecialchars($o['created_at']) ?></small>
          </div>
          <div><strong>Status:</strong> <?= htmlspecialchars($o['status']) ?></div>
        </div>
        <div>
          <strong>Items</strong>
          <ul class="order-items">
            <?php
              $items = json_decode($o['items'], true);
              if (is_array($items)){
                foreach ($items as $it){
                  echo '<li>' . htmlspecialchars($it['name']) . ' × ' . intval($it['qty']) . ' — ₦' . number_format($it['subtotal'],2) . '</li>';
                }
              }
            ?>
          </ul>
          <div style="text-align:right;margin-top:8px"><strong>Total: ₦<?= number_format($o['total'],2) ?></strong></div>
        </div>
      </div>
    <?php endforeach; endif; ?>
  </div>
</body>
</html>
