# Admin Panel Troubleshooting & Fix Guide

## 🚀 QUICK FIX (Do This First!)

### Step 1: Run the Setup Script
1. Go to: `http://localhost/CANTEEN%20APP/backEnd/setup_admin.php`
2. This will automatically:
   - ✅ Add the 'role' column to users table
   - ✅ Create the admin user
   - ✅ Add necessary database indexes

### Step 2: Test Login
1. Go to: `http://localhost/CANTEEN%20APP/backEnd/admin_login.php`
2. Enter:
   - **Email:** `admin@canteen.com`
   - **Password:** `admin123`
3. Click Login

### Step 3: Change Password
Once logged in, update your password in the database:
```sql
UPDATE users SET password_hash = 'newpassword' WHERE email = 'admin@canteen.com';
```

---

## 🔍 Troubleshooting Guide

### Issue 1: "Invalid email or password" Error

**Causes:**
1. Admin user doesn't exist in database
2. 'role' column missing from users table
3. Wrong credentials entered

**Solutions:**
1. Run `setup_admin.php` (see above)
2. Verify admin exists in database:
   ```sql
   SELECT id, first_name, email, role FROM users WHERE email = 'admin@canteen.com';
   ```
3. If no results, the admin wasn't created

### Issue 2: Database Connection Error

**Error Message:** "Database connection failed"

**Causes:**
1. XAMPP not running
2. MySQL not running
3. Wrong database credentials in db.php

**Solutions:**
1. Start XAMPP:
   - Open XAMPP Control Panel
   - Click "Start" next to Apache
   - Click "Start" next to MySQL
   
2. Verify db.php settings:
   ```php
   $DB_HOST = '127.0.0.1';    // Should be localhost or 127.0.0.1
   $DB_USER = 'root';          // Usually 'root'
   $DB_PASS = '';              // Usually empty for localhost
   $DB_NAME = 'canteen_app';   // Your database name
   ```

### Issue 3: "Table doesn't exist" Error

**Causes:**
1. Database tables weren't created
2. Wrong database selected

**Solutions:**
1. Verify database exists:
   ```sql
   SHOW DATABASES;
   ```
   
2. If `canteen_app` doesn't exist, create it:
   ```sql
   CREATE DATABASE canteen_app;
   USE canteen_app;
   ```
   
3. Import the schema from `canteen_app.sql` via phpMyAdmin

### Issue 4: Admin Dashboard Shows Blank/No Data

**Causes:**
1. No orders or users in database
2. PDO connection issue

**Solutions:**
1. Add test data:
   ```sql
   INSERT INTO menu_items (name, price, category) VALUES ('Test Item', 1000, 'breakfast');
   ```
   
2. Check browser console (F12) for JavaScript errors
3. Verify PDO is enabled in PHP

### Issue 5: "Call to undefined function" Error

**Causes:**
1. Missing include files
2. PHP version too old

**Solutions:**
1. Verify all files exist in backEnd folder:
   - admin_login.php ✅
   - admin_dashboard.php ✅
   - db.php ✅
   
2. Check PHP version (7.0+ required):
   - In phpMyAdmin, check "Server" tab
   - Or create `phpinfo.php` and check

---

## 📋 Manual Database Setup (If setup_admin.php Fails)

Run these SQL queries in phpMyAdmin:

```sql
-- 1. Create database
CREATE DATABASE IF NOT EXISTS canteen_app;
USE canteen_app;

-- 2. Add role column if missing
ALTER TABLE users ADD COLUMN role VARCHAR(20) DEFAULT 'user';

-- 3. Create admin user
INSERT INTO users (first_name, last_name, email, phone, password_hash, role) 
VALUES ('Admin', 'User', 'admin@canteen.com', '+2341234567890', 'admin123', 'admin')
ON DUPLICATE KEY UPDATE role='admin';

-- 4. Verify it worked
SELECT id, email, role FROM users WHERE email = 'admin@canteen.com';
```

---

## 🔐 Testing Locally

### Test Admin Access:
1. Open browser
2. Go to: `http://localhost/CANTEEN%20APP/backEnd/setup_admin.php`
3. Verify all checks pass (green ✅)
4. Go to: `http://localhost/CANTEEN%20APP/backEnd/admin_login.php`
5. Try login with:
   - Email: `admin@canteen.com`
   - Password: `admin123`

### If Still Failing:

1. **Check PHP Errors:**
   - Open: `c:\xampp\apache\logs\error.log`
   - Look for recent errors

2. **Check MySQL Errors:**
   - Open: `c:\xampp\mysql\data\` 
   - Check error log files

3. **Enable Error Display:**
   - Edit `php.ini` in XAMPP
   - Set: `display_errors = On`
   - Restart Apache

---

## 📊 Database Health Check

Run this to verify user table structure:

```sql
DESCRIBE users;
```

**Expected Output:**
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id | int | NO | PRI | NULL | auto_increment |
| first_name | varchar(100) | NO | | NULL | |
| last_name | varchar(100) | NO | | NULL | |
| email | varchar(255) | YES | UNI | NULL | |
| phone | varchar(50) | YES | | NULL | |
| password_hash | varchar(255) | NO | | NULL | |
| gender | varchar(20) | YES | | NULL | |
| **role** | **varchar(20)** | **YES** | | **user** | |
| created_at | timestamp | NO | | current_timestamp | |

---

## 🛠️ Common Fixes

### Fix 1: Reset Admin Password
```sql
UPDATE users 
SET password_hash = 'admin123' 
WHERE email = 'admin@canteen.com';
```

### Fix 2: Create New Admin
```sql
INSERT INTO users (first_name, last_name, email, password_hash, role) 
VALUES ('John', 'Admin', 'john@canteen.com', 'password123', 'admin');
```

### Fix 3: Enable Admin Role for User
```sql
UPDATE users 
SET role = 'admin' 
WHERE email = 'user@email.com';
```

### Fix 4: Remove Admin Role
```sql
UPDATE users 
SET role = 'user' 
WHERE email = 'admin@email.com';
```

---

## 📞 Advanced Troubleshooting

### Check Database Encoding:
```sql
SHOW CREATE DATABASE canteen_app;
SHOW CREATE TABLE users;
```

Should show: `utf8mb4`

### Check User Privileges:
```sql
SHOW GRANTS FOR 'root'@'localhost';
```

### Rebuild Indexes:
```sql
ANALYZE TABLE users;
OPTIMIZE TABLE users;
```

---

## ✅ Verification Checklist

- [ ] XAMPP is running (Apache + MySQL)
- [ ] Database `canteen_app` exists
- [ ] Table `users` exists with `role` column
- [ ] Admin user exists: `SELECT * FROM users WHERE role='admin';`
- [ ] db.php has correct credentials
- [ ] All admin PHP files exist in backEnd/
- [ ] Browser can access: `http://localhost/CANTEEN%20APP/backEnd/admin_login.php`
- [ ] Login page loads without errors
- [ ] Can login with admin@canteen.com / admin123
- [ ] Dashboard loads successfully

---

## 🚨 If Nothing Works

1. **Complete Reset:**
   ```sql
   DROP DATABASE canteen_app;
   CREATE DATABASE canteen_app;
   -- Run canteen_app.sql again
   -- Run setup_admin.php
   ```

2. **Check File Permissions:**
   - Right-click folder → Properties
   - Go to Security → Edit
   - Give your user "Full Control"

3. **Clear Browser Cache:**
   - Ctrl + Shift + Delete
   - Clear all cookies and cache
   - Reload page

4. **Check Firewall:**
   - Disable temporarily
   - Try accessing admin panel
   - If works, add XAMPP to whitelist

---

## 📝 Log Files to Check

1. **PHP Errors:** `c:\xampp\apache\logs\error.log`
2. **MySQL Errors:** `c:\xampp\mysql\data\[hostname].err`
3. **Browser Console:** F12 → Console tab
4. **Network Traffic:** F12 → Network tab

---

## 📞 Getting Help

Include these when asking for help:

1. Full error message (copy/paste)
2. What step are you on?
3. Operating system (Windows/Mac/Linux)
4. XAMPP version
5. PHP version (check phpMyAdmin)
6. MySQL version (check phpMyAdmin)

---

**Last Updated:** February 13, 2026  
**Version:** 1.0
