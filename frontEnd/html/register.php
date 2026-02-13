<?php
session_start();
$flash = $_SESSION['flash'] ?? null;
$old = $_SESSION['old'] ?? [];
unset($_SESSION['flash'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Register - SilverGold Canteen</title>
  <link rel="stylesheet" href="../css/style.css" />
  <style>
    body{background:linear-gradient(180deg,#f7f9fa,#fff);padding:0;margin:0}
    .auth-container{max-width:720px;margin:40px auto;padding:28px;background:#fff;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,0.06)}
    .auth-container h2{color:#2d6a4f;font-size:1.8rem;margin-bottom:12px}
    .auth-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .auth-grid input,.auth-grid select{padding:10px;border:1px solid #e6e6e6;border-radius:6px;font-size:14px}
    .auth-grid .full{grid-column:span 2}
    .actions{display:flex;gap:12px;align-items:center;margin-top:12px}
    .btn-primary{background:#2d6a4f;color:#fff;padding:10px 16px;border-radius:6px;border:none;cursor:pointer;font-size:14px;font-weight:600;min-height:44px}
    .btn-primary:hover{background:#1b4332}
    .flash{padding:10px;border-radius:6px;margin-bottom:12px;font-size:14px}
    .flash.error{background:#ffecec;color:#b00020}
    .flash.success{background:#eef7ee;color:#1b6b2f}
    .flash div{padding:4px 0}
    
    @media(max-width:768px){
      .auth-container{max-width:90%;margin:30px auto;padding:20px}
      .auth-container h2{font-size:1.5rem}
      .auth-grid{grid-template-columns:1fr;gap:10px}
      .auth-grid .full{grid-column:span 1}
      .auth-grid input,.auth-grid select{padding:10px;font-size:13px}
      .actions{flex-direction:column;gap:8px;align-items:stretch}
      .btn-primary{width:100%;padding:10px}
      .actions > div{text-align:center;font-size:13px}
    }
    
    @media(max-width:480px){
      body{padding:10px 0}
      .auth-container{max-width:95%;margin:15px auto;padding:16px}
      .auth-container h2{font-size:1.2rem;margin-bottom:10px}
      .auth-grid input,.auth-grid select{padding:8px;font-size:12px;margin-bottom:2px}
      .actions{margin-top:8px;gap:6px}
      .btn-primary{padding:10px 12px;font-size:12px}
      .flash{padding:8px;margin-bottom:10px;font-size:12px}
      .flash div{padding:3px 0}
    }
  </style>
</head>
<body>
  <div class="auth-container">
    <h2 style="color:#2d6a4f">Create account</h2>
    <?php if ($flash): ?>
      <div class="flash <?= $flash['type']=='error' ? 'error' : 'success' ?>">
        <?php foreach ($flash['messages'] as $m) echo '<div>'.htmlspecialchars($m).'</div>'; ?>
      </div>
    <?php endif; ?>
    <form action="../../backEnd/register.php" method="post">
      <div class="auth-grid">
        <input name="first_name" placeholder="First name" value="<?= htmlspecialchars($old['first_name'] ?? '') ?>" required />
        <input name="last_name" placeholder="Last name" value="<?= htmlspecialchars($old['last_name'] ?? '') ?>" required />
        <input name="email" type="email" placeholder="Email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" class="full" required />
        <input name="phone" placeholder="Phone" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" />
        <input name="password" type="password" placeholder="Password" required />
        <input name="password_confirm" type="password" placeholder="Confirm password" required />
        <select name="gender" class="full" required>
          <option value="" <?= empty($old['gender']) ? 'selected' : '' ?>>Gender</option>
          <option value="Male" <?= (isset($old['gender']) && $old['gender']=='Male') ? 'selected' : '' ?>>Male</option>
          <option value="Female" <?= (isset($old['gender']) && $old['gender']=='Female') ? 'selected' : '' ?>>Female</option>
          <option value="Other" <?= (isset($old['gender']) && $old['gender']=='Other') ? 'selected' : '' ?>>Other</option>
        </select>
      </div>
      <div class="actions">
        <button class="btn-primary" type="submit">Create account</button>
        <div style="margin-left:auto">Already have an account? <a href="login.php">Sign in</a></div>
      </div>
    </form>
  </div>
</body>
</html>
