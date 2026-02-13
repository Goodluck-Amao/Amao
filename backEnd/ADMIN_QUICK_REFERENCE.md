# Admin Panel Quick Reference Guide

## 🔐 LOGIN
**URL:** `http://localhost/CANTEEN%20APP/backEnd/admin_login.php`
- Email: `admin@canteen.com`
- Password: `admin123` (⚠️ Change immediately!)

---

## 📊 DASHBOARD
**Main Overview Page**

### Display Info:
- 👥 **Total Users** - All registered customers
- 📦 **Total Orders** - All orders placed
- ⏳ **Pending Orders** - Orders awaiting processing
- 💰 **Total Revenue** - Sum of all sales

### Recent Orders Table:
| Order ID | Customer | Amount | Status | Date | Action |
|----------|----------|--------|--------|------|--------|
| #1 | John Doe | ₦5,000 | Pending | Jan 15 | View |

---

## 🍽️ MENU ITEMS

### ✅ Add New Item
```
Name: Jollof Rice
Price: 2500.00
Category: [Breakfast|Lunch|Dinner|Snack|Beverage]
Image: frontEnd/image/jollof.jpg
Description: Seasoned rice with vegetables
```

### ✏️ Edit Item
- Click "Edit" button → Form loads → Change fields → Submit

### 🗑️ Delete Item
- Click "Delete" → Confirm → Item removed

---

## 📦 ORDER MANAGEMENT

### 🔄 Change Order Status
**Dropdown:** Pending → Processing → Ready → Completed

- **Pending**: Just received
- **Processing**: Being prepared
- **Ready**: Ready for pickup
- **Completed**: Delivered
- **Cancelled**: Cancelled

### 👁️ View Order Details
- Click "View" → Modal shows full info
- Info: Order ID, Customer, Items, Total, Date

---

## 👥 USER MANAGEMENT

### 📋 View Users
- See all registered users
- Info: ID, Name, Email, Phone, Gender, Joined Date

### 👁️ View User Details
- Click "View" → Shows full user profile
- Info: ID, Name, Email, Phone, Gender, Join Date

### 🗑️ Delete User
- Click "Delete" → Confirm → User removed
- ⚠️ Also removes associated orders

---

## 📈 REPORTS

### 📊 Dashboard Displays:
1. **Order Status Chart** (Pie chart)
   - Shows distribution of orders by status
   
2. **Monthly Revenue Chart** (Line chart)
   - Shows revenue trends over time

3. **Monthly Revenue Table**
   - Lists: Month | Orders | Revenue
   - Shows last 12 months

4. **Top Menu Items Table**
   - Lists: Name | Category | Times Ordered | Revenue
   - Shows most popular items

---

## 🎯 COMMON WORKFLOWS

### 📝 Add and Manage Menu Item
1. Click "Menu Items"
2. Fill form (Name, Price, Category)
3. Click "Add / Update Item"
4. ✅ Item appears in table
5. To edit: Click "Edit" → Modify → Submit
6. To delete: Click "Delete" → Confirm

### 📦 Process an Order
1. Dashboard shows pending orders
2. Click "Orders"
3. Find order in table
4. Change status: Pending → Processing
5. Change status: Processing → Ready
6. Notify customer it's ready
7. Change status: Ready → Completed

### 👤 Manage Users
1. Click "Users"
2. See all registered customers
3. To view: Click "View" button
4. To remove: Click "Delete" → Confirm

---

## 📊 KEY STATISTICS

### From Dashboard:
- **Total Users**: Count all customers
- **Pending Orders**: Orders awaiting processing
- **Monthly Revenue**: Sum of order totals
- **Order Status**: Distribution of order states

### From Reports:
- **Top Items**: Most ordered menu items
- **Revenue Trends**: Monthly performance
- **User Growth**: New registrations
- **Popular Categories**: Most ordered categories

---

## ⚙️ SETTINGS

### Change Default Password
```sql
UPDATE users 
SET password_hash = 'new_password_hash' 
WHERE email = 'admin@canteen.com';
```

### Add Another Admin
1. Login to MySQL/phpMyAdmin
2. Insert into users table with role='admin'
3. New admin can login with new credentials

### Backup Database
- Use phpMyAdmin: Database → Export
- Or MySQL: `mysqldump -u user -p canteen_app > backup.sql`

---

## 🚨 TROUBLESHOOTING

| Problem | Solution |
|---------|----------|
| Can't login | Check email/password in users table |
| Orders not showing | Run admin_migrations.sql |
| Images not loading | Verify image paths in database |
| Charts not displaying | Check browser console (F12) |
| Session timeout too quick | Increase SESSION_TIMEOUT value |

---

## 📱 MOBILE TIPS
- Admin panel is responsive
- Works on phone/tablet
- Touch-friendly buttons
- All features available

---

## 🔒 SECURITY REMINDERS
- ✅ Change default password immediately
- ✅ Never share admin credentials
- ✅ Use strong passwords (8+ chars, mix of letters/numbers)
- ✅ Regular database backups
- ✅ Log out when finished
- ✅ Update PHP and MySQL regularly

---

## 📞 HELP

### Check These First:
1. Database migrations executed
2. Admin account exists in users table
3. PHP version 7.0+
4. MySQL 5.7+
5. File permissions correct

### View Error Logs:
- Check browser console: Press F12
- Check PHP error log: Server logs folder
- Check MySQL logs: XAMPP logs folder

---

## 📂 NAVIGATION

```
Admin Panel Menu (Sidebar):
├── 📊 Dashboard
├── 🍽️ Menu Items
├── 📦 Orders
├── 👥 Users
├── 📈 Reports
└── 🚪 Logout
```

---

## ⏰ QUICK ACTIONS REFERENCE

| Action | Where | Steps |
|--------|-------|-------|
| Add menu item | Menu Items | Fill form → Submit |
| Update order status | Orders | Click dropdown → Select status |
| View user info | Users | Click "View" button |
| See analytics | Reports | Check charts & tables |
| Logout | Sidebar | Click "Logout" |

---

## 🎓 TRAINING CHECKLIST

- [ ] Understand login process
- [ ] Know order status workflow
- [ ] Can add menu items
- [ ] Can change order status
- [ ] Can view reports
- [ ] Know security best practices
- [ ] Know troubleshooting basics
- [ ] Can backup database

---

**Print this page for quick reference!**
Created: February 13, 2026
Version: 1.0
