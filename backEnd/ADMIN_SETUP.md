# Admin Panel Setup Guide

## Quick Start

### 1. Database Setup
Run the `admin_migrations.sql` file in phpMyAdmin:
- Go to phpMyAdmin
- Select the `canteen_app` database
- Click on "SQL" tab
- Copy and paste the contents of `admin_migrations.sql`
- Click "Go" to execute

**Default Admin Credentials:**
- Email: `admin@canteen.com`
- Password: `admin123`

⚠️ **IMPORTANT**: Change these credentials immediately after first login!

### 2. Access the Admin Panel
- URL: `http://localhost/CANTEEN%20APP/backEnd/admin_login.php`
- Login with the credentials above

### 3. Main Features

#### Dashboard (`admin_dashboard.php`)
- View key statistics (users, orders, revenue, pending orders)
- See recent orders at a glance
- Quick overview of system health

#### Menu Management (`admin_menu.php`)
- ✅ Add new menu items
- ✅ Edit existing items
- ✅ Delete menu items
- Manage categories (Breakfast, Lunch, Dinner, Snack, Beverage)
- Set pricing and descriptions

#### Order Management (`admin_orders.php`)
- View all orders with customer details
- Update order status (Pending → Processing → Ready → Completed)
- View detailed order information
- Track order history

#### User Management (`admin_users.php`)
- View all registered users
- See user details (name, email, phone, gender)
- Delete user accounts if needed
- Track user registration dates

#### Reports & Analytics (`admin_reports.php`)
- Monthly revenue trends
- Order status distribution (pie chart)
- Top menu items by sales
- User statistics (total, male, female)
- Revenue analytics

---

## File Structure

```
backEnd/
├── admin_login.php          # Admin authentication
├── admin_dashboard.php      # Main dashboard
├── admin_menu.php           # Menu management
├── admin_orders.php         # Order management
├── admin_users.php          # User management
├── admin_reports.php        # Analytics & reports
├── admin_logout.php         # Logout script
├── admin.css                # Admin panel styling
├── admin_migrations.sql     # Database setup
└── ADMIN_SETUP.md          # This file
```

---

## Order Statuses

| Status | Description |
|--------|-------------|
| **pending** | Order just received, awaiting process |
| **processing** | Order is being prepared |
| **ready** | Order is ready for pickup |
| **completed** | Order has been delivered/picked up |
| **cancelled** | Order was cancelled |

---

## Security Best Practices

1. **Change Default Password**
   - Login immediately after setup
   - Change admin password in the users table
   - Use bcrypt for password hashing

2. **Secure the Admin Panel**
   - Add HTTPS in production
   - Implement IP whitelisting if possible
   - Add additional authentication layers (2FA)

3. **Database Security**
   - Use prepared statements (already implemented)
   - Validate all inputs (implement server-side validation)
   - Regular database backups

4. **Access Control**
   - Only assign admin role to trusted staff
   - Log all admin actions (use admin_logs table)
   - Never share admin credentials

---

## Customization

### Change Admin Email
```sql
UPDATE users SET email='your_email@domain.com' WHERE role='admin' LIMIT 1;
```

### Change Admin Password (replace 'newpassword' with bcrypt hash)
```sql
UPDATE users SET password_hash='$2y$10$...' WHERE role='admin' LIMIT 1;
```

### Add Another Admin
```sql
INSERT INTO users (first_name, last_name, email, password_hash, role) 
VALUES ('Name', 'Surname', 'admin2@domain.com', 'hashed_password', 'admin');
```

---

## Troubleshooting

**Problem: Admin login not working**
- Solution: Verify database has been migrated (admin_migrations.sql executed)
- Check that user exists in database with role='admin'
- Verify password is correct

**Problem: Charts not displaying in Reports**
- Solution: Ensure Chart.js CDN is accessible
- Check browser console for errors
- Verify data exists in database

**Problem: Menu items not showing**
- Solution: Check that menu items exist in menu_items table
- Verify image paths are correct
- Clear browser cache

**Problem: Orders not updating**
- Solution: Check that orders table has 'status' column
- Verify user is logged in as admin
- Check browser console for JavaScript errors

---

## Performance Tips

1. **Database Optimization**
   - Regular backups
   - Cleanup old logs weekly
   - Archive completed orders monthly

2. **Frontend Optimization**
   - Cache static assets (CSS, JS)
   - Use lazy loading for images
   - Minify CSS and JavaScript

3. **Monitoring**
   - Monitor database size
   - Track page load times
   - Monitor active sessions

---

## Future Enhancements

- [ ] Implement email notifications for order status changes
- [ ] Add two-factor authentication (2FA)
- [ ] Create export to CSV/PDF functionality
- [ ] Add inventory management
- [ ] Implement role-based permissions (staff, manager, admin)
- [ ] Add real-time notifications
- [ ] Implement activity audit logs
- [ ] Add subscription plan management
- [ ] Create automated report emails
- [ ] Add multi-language support

---

## Support

For issues or questions:
1. Check the Troubleshooting section above
2. Review the database migrations
3. Check browser console for errors
4. Verify file permissions on server
5. Check PHP error logs

---

## Version Information

Admin Panel v1.0
- Created: 2026
- PHP: 7.0+
- MySQL: 5.7+
- Database: utf8mb4
