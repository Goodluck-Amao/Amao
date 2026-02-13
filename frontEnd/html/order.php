<?php
session_start();
require_once __DIR__ . '/../../backEnd/db.php';

// initialize cart
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_id'])) {
    $id = (int)$_POST['add_id'];
    $qty = max(1, (int)($_POST['quantity'] ?? 1));
    if (isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id] += $qty; else $_SESSION['cart'][$id] = $qty;
    header('Location: order.php');
    exit;
}

// remove from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $id = (int)$_POST['remove_id'];
    unset($_SESSION['cart'][$id]);
    header('Location: order.php');
    exit;
}

// update quantities
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update']) ) {
    foreach ($_POST['qty'] as $id => $q) {
        $id = (int)$id; $q = max(0, (int)$q);
        if ($q <= 0) unset($_SESSION['cart'][$id]); else $_SESSION['cart'][$id] = $q;
    }
    header('Location: order.php');
    exit;
}

// fetch menu items
$stmt = $pdo->query('SELECT id, name, description, price, image, category FROM menu_items ORDER BY created_at DESC');
$menu = $stmt->fetchAll(PDO::FETCH_ASSOC);

// prepare cart display
$cart = [];
$total = 0.0;
if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $s = $pdo->prepare("SELECT id, name, price FROM menu_items WHERE id IN ($placeholders)");
    $s->execute($ids);
    $items = $s->fetchAll(PDO::FETCH_ASSOC);
    foreach ($items as $it) {
        $id = $it['id'];
        $qty = $_SESSION['cart'][$id] ?? 0;
        $subtotal = $it['price'] * $qty;
        $cart[] = ['id'=>$id,'name'=>$it['name'],'price'=>$it['price'],'qty'=>$qty,'subtotal'=>$subtotal];
        $total += $subtotal;
    }
}

$flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Order - SilverGold Canteen</title>
  <link rel="stylesheet" href="../css/style.css" />
  <style>
    .order-page{max-width:1100px;margin:24px auto;padding:12px}
    .order-page h2{font-size:1.8rem;margin-bottom:16px}
    .menu-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px}
    .menu-card{padding:8px;border:1px solid #ddd;border-radius:8px}
    .menu-card img{height:140px;object-fit:cover;width:100%;border-radius:4px}
    .menu-card h3{margin:8px 0;font-size:1rem}
    .menu-card p{margin:4px 0;font-size:0.9rem}
    .menu-card button{padding:8px 12px;font-size:0.9rem;cursor:pointer;background:#333;color:#fff;border:none;border-radius:4px;min-height:36px}
    .menu-card input[type="number"]{padding:6px;font-size:0.9rem}
    .cart{position:fixed;right:18px;top:80px;width:320px;background:#fff;border-radius:10px;padding:12px;box-shadow:0 6px 20px rgba(0,0,0,0.08);max-height:80vh;overflow-y:auto}
    .cart h4{margin:0 0 8px;font-size:1rem}
    .cart table{width:100%;border-collapse:collapse;font-size:0.9rem}
    .cart td{padding:6px 4px;border-bottom:1px solid #eee}
    .cart input[type="number"]{width:50px;padding:4px;font-size:0.85rem}
    .cart .total{font-weight:700;text-align:right;border-top:2px solid #333;padding-top:8px}
    .cart form button{width:100%;padding:10px;margin-top:8px;background:#333;color:#fff;border:none;border-radius:4px;cursor:pointer;font-size:0.9rem;min-height:44px}
    .cart form button:disabled{background:#ccc;cursor:not-allowed}
    
    @media(max-width:900px){
      .cart{position:static;width:auto;margin-top:16px;max-height:none}
      .menu-grid{grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:12px}
    }
    
    @media(max-width:768px){
      .order-page{padding:8px;margin:16px auto}
      .order-page h2{font-size:1.5rem}
      .menu-grid{grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:10px}
      .menu-card img{height:110px}
      .menu-card h3{font-size:0.9rem;margin:6px 0}
      .menu-card p{font-size:0.8rem;margin:3px 0}
      .menu-card button{padding:6px 10px;font-size:0.8rem;min-height:32px}
      .cart{width:100%;padding:10px;margin-top:12px}
      .cart table{font-size:0.85rem}
      .cart td{padding:4px 2px}
      .cart input[type="number"]{width:40px;padding:3px;font-size:0.8rem}
      .cart form button{padding:8px;font-size:0.85rem;min-height:40px}
    }
    
    @media(max-width:480px){
      .order-page{padding:6px;margin:12px auto}
      .order-page h2{font-size:1.2rem;margin-bottom:12px}
      .menu-grid{grid-template-columns:repeat(2,1fr);gap:8px}
      .menu-card{padding:6px}
      .menu-card img{height:90px}
      .menu-card h3{font-size:0.8rem;margin:4px 0}
      .menu-card p{font-size:0.75rem;margin:2px 0}
      .menu-card button{padding:5px 8px;font-size:0.7rem;min-height:30px}
      .menu-card input[type="number"]{width:50px;padding:4px;font-size:0.8rem}
      .cart{width:100%;padding:8px;margin-top:10px}
      .cart h4{font-size:0.9rem}
      .cart table{font-size:0.8rem}
      .cart td{padding:3px 2px}
      .cart input[type="number"]{width:35px;padding:2px;font-size:0.75rem}
      .cart form button{padding:6px;font-size:0.8rem;min-height:36px;margin-top:6px}
    }
  </style>
</head>
<body>
  <div class="order-page">
    <h2>Menu <a href="order_history.php" style="font-size:14px;margin-left:12px">(View Order History)</a></h2>
    <?php if ($flash): ?>
      <div class="flash <?= $flash['type']=='error' ? 'error' : 'success' ?>">
        <?php foreach ($flash['messages'] as $m) echo '<div>'.htmlspecialchars($m).'</div>'; ?>
      </div>
    <?php endif; ?>

    <div class="menu-grid">
      <?php foreach ($menu as $m): ?>
        <div class="menu-card">
          <img src="../image/<?= htmlspecialchars(basename($m['image'])) ?>" alt="<?= htmlspecialchars($m['name']) ?>" />
          <h3><?= htmlspecialchars($m['name']) ?></h3>
          <p><?= htmlspecialchars($m['description']) ?></p>
          <p class="price">₦<?= number_format($m['price'],2) ?></p>
          <form method="post" style="display:flex;gap:8px;align-items:center">
            <input type="hidden" name="add_id" value="<?= $m['id'] ?>" />
            <input type="number" name="quantity" value="1" min="1" style="width:70px;padding:6px" />
            <button type="submit">Add</button>
          </form>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="cart">
      <h4>Your Cart</h4>
      <form method="post">
        <table>
          <?php if (empty($cart)): ?>
            <tr><td>Your cart is empty</td></tr>
          <?php else: foreach ($cart as $c): ?>
            <tr>
              <td><?= htmlspecialchars($c['name']) ?> x <input type="number" name="qty[<?= $c['id'] ?>]" value="<?= $c['qty'] ?>" min="0" style="width:60px"/></td>
              <td style="text-align:right">₦<?= number_format($c['subtotal'],2) ?></td>
            </tr>
          <?php endforeach; endif; ?>
          <tr><td class="total">Total</td><td class="total">₦<?= number_format($total,2) ?></td></tr>
        </table>
        <div style="display:flex;gap:8px;margin-top:8px">
          <button type="submit" name="update">Update</button>
        </div>
      </form>
      <form method="post" action="../../backEnd/place_order.php" style="margin-top:10px">
        <button type="submit" <?= empty($cart) ? 'disabled' : '' ?>>Place Order</button>
      </form>
    </div>
  </div>
</body>
</html>
