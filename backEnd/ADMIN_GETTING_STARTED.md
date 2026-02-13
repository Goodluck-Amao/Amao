# Admin Panel - Complete Setup Instructions

## ⚡ FASTEST WAY TO GET IT WORKING (3 Minutes)

### Step 1: Let the Setup Script Do It All
```
1. Open your browser
2. Go to: http://localhost/CANTEEN%20APP/backEnd/setup_admin.php
3. You should see green checkmarks ✅
4. Continue to Step 2
```

### Step 2: Login to Admin
```
1. Go to: http://localhost/CANTEEN%20APP/backEnd/admin_login.php
2. Email: admin@canteen.com
3. Password: admin123
4. Click Login
```

### Step 3: Change Your Password (IMPORTANT!)
```
1. Open phpMyAdmin
2. Go to: canteen_app database → users table
3. Find the row where email = 'admin@canteen.com'
4. Right-click → Edit
5. Change password_hash to your new password
6. Click Save
```

**Done! You now have a working admin panel!** 🎉

---

## 📋 What Each Admin Panel File Does

| File | Purpose | Access |
|------|---------|--------|
| `admin_login.php` | Login page | `http://localhost/CANTEEN%20APP/backEnd/admin_login.php` |
| `admin_dashboard.php` | Main dashboard | Access after login |
| `admin_menu.php` | Manage menu items | Access after login |
| `admin_orders.php` | Manage orders | Access after login |
| `admin_users.php` | Manage users | Access after login |
| `admin_reports.php` | View analytics | Access after login |
| `admin.css` | Styling | Automatically loaded |
| `setup_admin.php` | Setup database | Run once |
| `db.php` | Database connection | Used by all pages |

---

## 🔍 If You Get an Error on setup_admin.php

### Problem: "Error checking role column"

**Solution:** Manually add the column:
1. Open phpMyAdmin
2. Go to: canteen_app → users table
3. Click "Structure" tab
4. Scroll to bottom, click "Add"
5. Enter:
   - Field name: `role`
   - Type: `VARCHAR`
   - Length: `20`
   - Click "Save"

### Problem: "Database connection failed"

**Solution:**
1. Check XAMPP is running (red = stopped, green = running)
2. Start Apache and MySQL if needed
3. Verify db.php file has correct settings

### Problem: Admin user already exists error

**Solution:** That's OK! This means it was already created. Try logging in with:
- Email: `admin@canteen.com`
- Password: `admin123`

---

## 🧪 Quick Test to Verify Everything Works

### Test 1: Database Connection
1. Create a file named `test_db.php` in backEnd folder
2. Paste this code:
```php
<?php
include 'db.php';
try {
    $result = $pdo->query("SELECT COUNT(*) FROM users");
    echo "✅ Database works! Users: " . $result->fetchColumn();
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
```
3. Open: `http://localhost/CANTEEN%20APP/backEnd/test_db.php`
4. You should see: `✅ Database works!`

### Test 2: Admin Login
1. Go to: `http://localhost/CANTEEN%20APP/backEnd/setup_admin.php`
2. Verify all checks pass (should all be green ✅)
3. Go to: `http://localhost/CANTEEN%20APP/backEnd/admin_login.php`
4. Try: `admin@canteen.com` / `admin123`
5. You should see the dashboard

### Test 3: Admin Panel Works
1. Once logged in, click each menu item:
   - Dashboard (should show stats)
   - Menu Items (should show empty or populated)
   - Orders (should show any existing orders)
   - Users (should show registered users)
   - Reports (should show charts)

---

## 📊 What You Can Do in Admin Panel

### Dashboard
- See total users count
- See total orders count
- See pending orders count
- See total revenue
- View recent orders

### Menu Items
- ➕ Add new food items
- ✏️ Edit existing items
- 🗑️ Delete items
- Set prices
- Set categories (Breakfast, Lunch, Dinner, Snack, Beverage)

### Orders
- 👁️ View all orders
- 🔄 Change order status (Pending → Processing → Ready → Completed)
- 📋 See customer information
- 📝 See order details

### Users
- 👥 View all registered users
- 👁️ See user details
- 🗑️ Delete user accounts (if needed)

### Reports
- 📈 See monthly revenue chart
- 📊 See order status distribution
- 📋 See top menu items
- 👥 See user statistics

---

## 🔐 Security Notes

1. **Change Default Password Immediately!**
   - Current: `admin123`
   - Very easy to guess - change it!

2. **Use Strong Passwords**
   - Minimum 8 characters
   - Mix of letters, numbers, symbols
   - Don't use easy words like "password"

3. **Never Share Admin Credentials**
   - Only give to trusted staff
   - Don't put in emails
   - Don't write on sticky notes

4. **Regular Backups**
   - Backup database weekly
   - Save to external drive or cloud
   - Can restore if data is lost

---

## 📞 Help Commands

### View Admin User
```sql
SELECT id, first_name, email, role FROM users WHERE role = 'admin';
```

### Reset Admin Password
```sql
UPDATE users SET password_hash = 'newpassword' WHERE email = 'admin@canteen.com';
```

### Create Another Admin
```sql
INSERT INTO users (first_name, last_name, email, password_hash, role) VALUES ('John', 'Doe', 'john@canteen.com', 'password123', 'admin');
```

### View All Users
```sql
SELECT id, first_name, last_name, email, role FROM users;
```

### View All Orders
```sql
SELECT id, user_id, total, status, created_at FROM orders;
```

### View Menu Items
```sql
SELECT id, name, price, category FROM menu_items;
```

---

## 🎯 Next Steps After Setup

1. ✅ Run setup_admin.php
2. ✅ Test login with admin@canteen.com / admin123
3. ✅ Change password to something secure
4. ✅ Add some test menu items via admin panel
5. ✅ Test order creation from frontend
6. ✅ View orders in admin panel
7. ✅ Update order status
8. ✅ Check dashboard for statistics
9. ✅ View reports and charts
10. ✅ Train other staff on how to use it

---

## 📱 Mobile Access

The admin panel works on mobile/tablet:
- ✅ Responsive design
- ✅ Touch-friendly buttons
- ✅ Works on any device
- ✅ Just go to same URL on phone

---

## 🚨 Emergency Procedures

### If Admin Can't Login
1. Run setup_admin.php again
2. Try password: `admin123` (reset password)
3. If still fails, manually enter in database

### If Database Gets Corrupted
1. Go to phpMyAdmin
2. Select canteen_app database
3. Click "Export"
4. Backup the SQL file
5. Then try to fix or reload

### If You Forgot Admin Password
```sql
UPDATE users SET password_hash = 'admin123' WHERE email = 'admin@canteen.com';
```
Then login with temporary password and change it.

---

## ✅ Acceptance Checklist

- [ ] setup_admin.php runs with all green checks
- [ ] Can login to admin_login.php
- [ ] Dashboard loads and shows stats
- [ ] Can add a menu item
- [ ] Can view menu items
- [ ] Can view orders (if any exist)
- [ ] Can change order status
- [ ] Can view users
- [ ] Can view reports/charts
- [ ] Password has been changed from default
- [ ] At least 2 users have tested it

---

## 📞 Common Questions

**Q: Can I delete the setup_admin.php file?**
A: Yes, after running it once, you can delete it for security.

**Q: What if I need to reset everything?**
A: Just run setup_admin.php again - it will reinitialize everything.

**Q: Can I have multiple admins?**
A: Yes! Use the SQL command in "Help Commands" section above.

**Q: How do I backup the database?**
A: Use phpMyAdmin Export feature, or MySQL backup commands.

**Q: What if users can't see my changes?**
A: Refresh their browser, or they may need to logout and login again.

**Q: Why does the admin panel look different than user website?**
A: Admin panel is designed for functionality, not aesthetics. User site is designed for customers.

---

## 🎓 Training Your Staff

Print this document and train each admin on:

1. **How to Login**
2. **How to Add Menu Items**
3. **How to Update Order Status**
4. **How to View Orders & Users**
5. **How to Check Reports**
6. **When to Contact IT (password issues, crashes)**

Training typically takes 30 minutes.

---

## 📞 Getting Help

If something doesn't work:
1. Read ADMIN_TROUBLESHOOTING.md
2. Run setup_admin.php again
3. Check browser console (F12)
4. Check PHP errors in logs
5. Verify database is running in XAMPP
6. Try in different browser
7. Restart XAMPP completely

---

**Enjoy your new admin panel! 🎉**

If you have questions, refer to:
- ADMIN_TROUBLESHOOTING.md - For problems
- ADMIN_DOCUMENTATION.md - For features
- ADMIN_QUICK_REFERENCE.md - For quick commands
