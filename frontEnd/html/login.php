<?php
session_start();
if (!empty($_SESSION['user_id'])) {
    header('Location: order.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - SilverGold Canteen</title>
  <link rel="stylesheet" href="../css/style.css" />
  <style>
    .auth-container{max-width:420px;margin:60px auto;padding:28px;background:#fff;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,0.08)}
    .auth-container h2{text-align:center;color:#2d6a4f;margin-bottom:12px;font-size:1.8rem}
    .auth-container form{display:flex;flex-direction:column;gap:12px}
    .auth-container input{padding:12px;border:1px solid #e6e6e6;border-radius:6px;font-size:16px}
    .auth-container button{background:#2d6a4f;color:#fff;padding:12px;border:none;border-radius:6px;font-weight:600;cursor:pointer;min-height:44px;font-size:16px}
    .auth-footer{display:flex;justify-content:space-between;align-items:center;margin-top:8px;font-size:14px}
    .auth-footer a{color:#2d6a4f;text-decoration:none}
    body{background:linear-gradient(180deg,#f7f9fa,#fff);padding:0;margin:0}
    .flash{padding:12px;margin-bottom:12px;border-radius:6px;font-size:14px}
    .flash.error{background:#ffebee;color:#c62828}
    .flash.success{background:#e8f5e9;color:#2e7d32}
    
    @media(max-width:768px){
      .auth-container{max-width:95%;margin:40px auto;padding:20px}
      .auth-container h2{font-size:1.5rem;margin-bottom:16px}
      .auth-container input{padding:10px;font-size:14px}
      .auth-container button{padding:10px;font-size:14px}
      .auth-footer{flex-direction:column;gap:8px;text-align:center}
    }
    
    @media(max-width:480px){
      body{padding:10px 0}
      .auth-container{max-width:95%;margin:20px auto;padding:16px}
      .auth-container h2{font-size:1.2rem;margin-bottom:12px}
      .auth-container input{padding:10px;font-size:13px;margin-bottom:4px}
      .auth-container button{padding:10px;font-size:13px;margin-top:4px}
      .auth-footer{flex-direction:column;gap:8px;font-size:12px;margin-top:12px}
      .auth-footer a{display:block;margin-top:4px}
      .flash div{font-size:12px;padding:6px}
    }
  </style>
</head>
<body>
  <div class="auth-container">
    <h2>Sign in</h2>
    <?php $flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); if ($flash): ?>
      <div class="flash <?= $flash['type']=='error' ? 'error' : 'success' ?>">
        <?php foreach ($flash['messages'] as $m) echo '<div>'.htmlspecialchars($m).'</div>'; ?>
      </div>
    <?php endif; ?>
    <form action="../../backEnd/login.php" method="post">
      <input type="text" name="username" placeholder="Email, phone or full name" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit">Log in</button>
    </form>
    <div class="auth-footer">
      <span>Don't have an account? <a href="register.php">Sign up</a></span>
      <a href="index.html">Home</a>
    </div>
  </div>
</body>
</html>
