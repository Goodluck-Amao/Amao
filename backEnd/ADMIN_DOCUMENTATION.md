# CANTEEN APP ADMIN PANEL - COMPLETE DOCUMENTATION

## 📋 Overview

The Admin Panel is a comprehensive management system for controlling all aspects of the Canteen App. It provides administrators with tools to manage menus, orders, users, and view detailed analytics.

---

## 🚀 Quick Access

| Module | URL | Purpose |
|--------|-----|---------|
| **Admin Login** | `/backEnd/admin_login.php` | Admin authentication |
| **Dashboard** | `/backEnd/admin_dashboard.php` | Overview & statistics |
| **Menu Management** | `/backEnd/admin_menu.php` | Add/Edit/Delete items |
| **Orders** | `/backEnd/admin_orders.php` | Manage orders & status |
| **Users** | `/backEnd/admin_users.php` | View & manage users |
| **Reports** | `/backEnd/admin_reports.php` | Analytics & insights |

---

## 🔐 Authentication

### Login Process
1. Navigate to `admin_login.php`
2. Enter email and password
3. System verifies admin role in database
4. Creates session and redirects to dashboard

### Default Credentials (⚠️ Change immediately!)
```
Email: admin@canteen.com
Password: admin123
```

### Session Management
- Session timeout: 1 hour
- Auto-logout when browser closes
- Logout link available in sidebar

---

## 📊 Dashboard Features

### Key Metrics (Real-time)
- **Total Users**: All registered customers
- **Total Orders**: All orders in system
- **Pending Orders**: Orders awaiting processing
- **Total Revenue**: Sum of all order amounts

### Recent Orders Widget
- Shows last 5 orders
- Displays customer, amount, status, date
- Quick access to order details

### Status Colors
- 🟡 **Pending**: Order received
- 🟦 **Processing**: Being prepared
- 🟩 **Ready**: Ready for pickup
- ✅ **Completed**: Delivered
- ⛔ **Cancelled**: Cancelled order

---

## 🍽️ Menu Management

### Adding Menu Items
1. Go to **Menu Items** section
2. Fill in the form:
   - **Item Name** (required)
   - **Price** (required, in ₦)
   - **Category** (required)
   - **Image URL** (optional)
   - **Description** (optional)
3. Click "Add / Update Item"

### Categories Available
- Breakfast
- Lunch
- Dinner
- Snack
- Beverage

### Editing Items
1. Click "✏️ Edit" button next to item
2. Form auto-populates with item data
3. Modify fields as needed
4. Click "Add / Update Item" to save

### Deleting Items
1. Click "🗑️ Delete" button
2. Confirm deletion in popup
3. Item removed from menu

### Price Format
- Format: `2500.00`
- Currency: Naira (₦)
- Supports decimals for pricing

---

## 📦 Order Management

### Viewing Orders
- All orders listed in table
- Shows: Order ID, Customer, Email, Phone, Amount, Status, Date
- Orders sorted by newest first

### Changing Order Status
1. Click status dropdown next to order
2. Select new status:
   - **Pending** → Initial state
   - **Processing** → Kitchen preparing
   - **Ready** → Ready for pickup/delivery
   - **Completed** → Delivered
   - **Cancelled** → Order cancelled
3. Form auto-submits

### Viewing Order Details
1. Click "👁️ View" button
2. Modal shows:
   - Order ID
   - Customer info (name, email, phone)
   - Items ordered
   - Total amount
   - Delivery address (if available)
3. Close modal with X button

### Order Workflow
```
Pending → Processing → Ready → Completed
   ↓                      ↓
Cancelled            (Can cancel anytime)
```

---

## 👥 User Management

### Viewing Users
- All registered customers listed
- Shows: ID, Name, Email, Phone, Gender, Joined Date
- Users sorted by newest first

### User Information
- **Full Name**: First + Last name
- **Email**: Contact email
- **Phone**: Contact number
- **Gender**: Male/Female/Other
- **Joined Date**: Registration date

### User Actions
- **View**: See detailed user information
- **Delete**: Remove user account
  - ⚠️ Warning: Deletes all associated data
  - Confirmation required

### User Statistics (on Dashboard)
- Total registered users
- Male users count
- Female users count
- Growth trends

---

## 📈 Reports & Analytics

### Monthly Revenue Report
- Shows revenue by month
- Number of orders per month
- Last 12 months displayed
- Trend analysis

### Order Status Distribution
- Pie chart showing order breakdown
- Counts by status: Pending, Processing, Ready, Completed, Cancelled
- Visual distribution

### Monthly Revenue Chart
- Line graph of revenue trends
- Helps identify peak seasons
- Data points for each month

### Top Menu Items
- Best-selling items listed
- Shows: Name, Category, Times Ordered, Revenue
- Help identify popular items
- Data for inventory planning

---

## 💾 Database Structure

### Users Table
```
id, first_name, last_name, email, phone, 
password_hash, gender, role, created_at
```

### Menu Items Table
```
id, name, description, price, image, 
category, created_at
```

### Orders Table
```
id, user_id, items, total, status, created_at
```

### Subscriptions Table
```
id, user_id, plan, starts_at, ends_at, created_at
```

### Admin Logs Table (Optional)
```
id, admin_id, action, description, created_at
```

---

## 🛡️ Security Features

1. **Session Authentication**
   - Checks admin login in every protected page
   - Redirects to login if not authenticated

2. **Input Validation**
   - Server-side validation on forms
   - Prevents empty submissions
   - Price/amount validation

3. **SQL Protection**
   - Uses prepared statements
   - Protects against SQL injection
   - Parameterized queries

4. **Password Security**
   - Passwords hashed (not plaintext)
   - Use bcrypt in production
   - Never send passwords via email

5. **XSS Prevention**
   - Output sanitization
   - htmlspecialchars() on all displays
   - Prevents malicious script injection

---

## 📱 Responsive Design

The admin panel is fully responsive:
- **Desktop**: Full layout with sidebar
- **Tablet**: Optimized grid layouts
- **Mobile**: Touch-friendly navigation
- All features accessible on any device

---

## ⚙️ Settings & Customization

### Configuration File: `admin_config.php`

Edit these settings:
```php
define('ITEMS_PER_PAGE', 20);           // Items per page
define('SESSION_TIMEOUT', 3600);        // Session timeout
define('CURRENCY_SYMBOL', '₦');         // Currency
define('DECIMAL_PLACES', 2);            // Price decimals
```

### Category Customization
Add/remove categories in `$CATEGORIES` array:
```php
'snack' => 'Snack',
'beverage' => 'Beverage',
```

---

## 📝 Common Tasks

### Create New Admin Account
```sql
INSERT INTO users (first_name, last_name, email, password_hash, role) 
VALUES ('John', 'Doe', 'john@canteen.com', 'password_hash', 'admin');
```

### Change Admin Password
```sql
UPDATE users 
SET password_hash = 'new_hashed_password' 
WHERE email = 'admin@canteen.com';
```

### Backup Database
- Use phpMyAdmin Export feature
- Or MySQL command line: `mysqldump -u user -p database > backup.sql`

### View Admin Activity Logs
```sql
SELECT * FROM admin_logs ORDER BY created_at DESC LIMIT 50;
```

---

## 🐛 Troubleshooting

### Issue: Admin login fails
**Solution:**
- Verify email in users table with WHERE role='admin'
- Check password matches (case-sensitive)
- Ensure database migration was run

### Issue: Orders not showing
**Solution:**
- Verify orders table has all required columns
- Check database connection in db.php
- Look for SQL errors in error log

### Issue: Charts not displaying
**Solution:**
- Check Chart.js CDN is accessible
- Open browser console (F12) for errors
- Verify data exists in database

### Issue: Images not loading
**Solution:**
- Verify image paths are correct
- Check image folder exists
- Ensure file permissions are set

### Issue: Session timeout too quick
**Solution:**
- Increase SESSION_TIMEOUT in admin_config.php
- Change PHP.ini session.gc_maxlifetime

---

## 📞 Support Checklist

Before contacting support:
- [ ] Database migration executed (admin_migrations.sql)
- [ ] Credentials verified in users table
- [ ] File permissions set correctly
- [ ] PHP version 7.0+
- [ ] MySQL 5.7+
- [ ] Browser console checked for errors
- [ ] Database backups exist

---

## 🔄 Maintenance Schedule

### Daily
- Monitor pending orders
- Check system performance

### Weekly
- Review order trends
- Check for errors in logs
- Backup database

### Monthly
- Archive old orders
- Review user registrations
- Update menu as needed
- Analyze revenue reports

### Quarterly
- Update security patches
- Review admin access
- Optimize database

---

## 📂 File Structure

```
backEnd/
├── admin_login.php           # Login page
├── admin_dashboard.php       # Main dashboard
├── admin_menu.php            # Menu management
├── admin_orders.php          # Order management
├── admin_users.php           # User management
├── admin_reports.php         # Reports & analytics
├── admin_logout.php          # Logout handler
├── admin.css                 # Admin styling
├── admin_config.php          # Configuration
├── admin_migrations.sql      # Database setup
├── ADMIN_DOCUMENTATION.md    # This file
├── ADMIN_SETUP.md            # Setup guide
├── db.php                    # Database connection
└── ...other files
```

---

## 🎨 UI Features

### Color Scheme
- Primary: #2c3e50 (Dark Blue)
- Secondary: #3498db (Light Blue)
- Success: #27ae60 (Green)
- Warning: #f39c12 (Orange)
- Error: #e74c3c (Red)

### Typography
- Font: Segoe UI, Tahoma, Geneva
- Headings: Bold, larger sizes
- Body: Regular weight for readability

### Icons Used
- 🍽️ Menu Items
- 📦 Orders
- 👥 Users
- 📊 Dashboard
- 📈 Reports
- ✏️ Edit
- 🗑️ Delete
- 👁️ View
- 🔐 Login
- 🚪 Logout

---

## 📊 Analytics Glossary

| Term | Definition |
|------|-----------|
| **Conversion Rate** | Percentage of orders completed |
| **Average Order Value** | Total revenue ÷ Number of orders |
| **Peak Hour** | Time with most orders |
| **Monthly Revenue** | Total money earned in a month |
| **User Retention** | % of returning customers |

---

## 🚀 Performance Tips

1. **Database**
   - Regular backups
   - Index frequently queried columns
   - Archive old data

2. **Frontend**
   - Compress images
   - Cache static files
   - Load charts only when needed

3. **Server**
   - Monitor disk space
   - Check PHP memory limits
   - Monitor CPU usage

---

## 📚 Related Resources

- [Setup Guide](ADMIN_SETUP.md)
- [Configuration File](admin_config.php)
- [Database Migrations](admin_migrations.sql)
- [Main App Documentation](../README.md)

---

**Last Updated:** February 13, 2026  
**Version:** 1.0  
**Status:** Production Ready ✅

For questions or issues, refer to the Troubleshooting section or contact system administrator.
