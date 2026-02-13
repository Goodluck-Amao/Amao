<?php
/**
 * Admin Panel Configuration File
 * Contains configuration constants for the admin panel
 */

// Admin Panel Settings
define('ADMIN_PANEL_VERSION', '1.0');
define('ADMIN_TITLE', 'Canteen App Admin Panel');

// Database Configuration (inherited from db.php)
// These are already set in db.php

// Admin Features
define('ENABLE_ADMIN_LOGS', true);      // Log all admin actions
define('ENABLE_ORDER_HISTORY', true);   // Keep order history
define('ENABLE_BACKUPS', true);         // Backup important data

// Pagination
define('ITEMS_PER_PAGE', 20);

// File Upload Settings
define('MAX_IMAGE_SIZE', 5242880);      // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);
define('UPLOAD_DIR', '../frontEnd/image/');

// Session Settings
define('SESSION_TIMEOUT', 3600);        // 1 hour in seconds

// Security Settings
define('USE_BCRYPT', true);             // Use bcrypt for password hashing
define('BCRYPT_COST', 10);              // Bcrypt cost parameter

// Email Notifications (optional)
define('SEND_ORDER_NOTIFICATIONS', false);
define('ADMIN_EMAIL', 'admin@canteen.com');
define('SMTP_HOST', '');
define('SMTP_PORT', 587);

// Report Settings
define('REPORT_DATE_FORMAT', 'Y-m-d');
define('CURRENCY_SYMBOL', '₦');
define('DECIMAL_PLACES', 2);

// Category List
$CATEGORIES = [
    'breakfast' => 'Breakfast',
    'lunch' => 'Lunch',
    'dinner' => 'Dinner',
    'snack' => 'Snack',
    'beverage' => 'Beverage'
];

// Order Status List
$ORDER_STATUSES = [
    'pending' => 'Pending',
    'processing' => 'Processing',
    'ready' => 'Ready',
    'completed' => 'Completed',
    'cancelled' => 'Cancelled'
];

// Functions for admin operations
function logAdminAction($conn, $admin_id, $action, $description) {
    if (!ENABLE_ADMIN_LOGS) return;
    
    try {
        $stmt = $conn->prepare("INSERT INTO admin_logs (admin_id, action, description) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $admin_id, $action, $description);
        $stmt->execute();
        $stmt->close();
    } catch (Exception $e) {
        error_log("Error logging admin action: " . $e->getMessage());
    }
}

function formatCurrency($amount) {
    return CURRENCY_SYMBOL . number_format($amount, DECIMAL_PLACES);
}

function formatDate($date) {
    return date(REPORT_DATE_FORMAT, strtotime($date));
}

function isValidImageType($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($ext, ALLOWED_IMAGE_TYPES);
}

function generateOrderNumber() {
    return 'ORD-' . strtoupper(uniqid());
}

function getOrderStats($conn) {
    $stats = [
        'total_orders' => 0,
        'pending_orders' => 0,
        'completed_orders' => 0,
        'total_revenue' => 0
    ];
    
    try {
        $result = $conn->query("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status='pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status='completed' THEN 1 ELSE 0 END) as completed,
                SUM(total) as revenue
            FROM orders
        ");
        
        if ($result) {
            $row = $result->fetch_assoc();
            $stats['total_orders'] = $row['total'] ?? 0;
            $stats['pending_orders'] = $row['pending'] ?? 0;
            $stats['completed_orders'] = $row['completed'] ?? 0;
            $stats['total_revenue'] = $row['revenue'] ?? 0;
        }
    } catch (Exception $e) {
        error_log("Error getting order stats: " . $e->getMessage());
    }
    
    return $stats;
}

function getUserStats($conn) {
    $stats = [
        'total_users' => 0,
        'new_users_today' => 0,
        'new_users_week' => 0
    ];
    
    try {
        $result = $conn->query("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today,
                SUM(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 ELSE 0 END) as week
            FROM users
            WHERE role != 'admin'
        ");
        
        if ($result) {
            $row = $result->fetch_assoc();
            $stats['total_users'] = $row['total'] ?? 0;
            $stats['new_users_today'] = $row['today'] ?? 0;
            $stats['new_users_week'] = $row['week'] ?? 0;
        }
    } catch (Exception $e) {
        error_log("Error getting user stats: " . $e->getMessage());
    }
    
    return $stats;
}

function getTopMenuItems($conn, $limit = 10) {
    $items = [];
    
    try {
        $query = "
            SELECT name, category, price, COUNT(*) as order_count
            FROM menu_items
            GROUP BY id
            ORDER BY order_count DESC
            LIMIT ?
        ";
        
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("i", $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
            $stmt->close();
        }
    } catch (Exception $e) {
        error_log("Error getting top items: " . $e->getMessage());
    }
    
    return $items;
}

/**
 * Sanitize input to prevent XSS
 */
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email format
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Validate password strength
 */
function validatePassword($password) {
    // At least 6 characters
    return strlen($password) >= 6;
}

?>
