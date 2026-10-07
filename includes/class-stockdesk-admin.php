<?php
if (!defined('ABSPATH')) {
    exit;
}

class StockDesk_Admin {
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'menu'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'assets'));
    }

    public static function menu() {
        add_menu_page('StockDesk', 'Inventory', 'manage_options', 'stockdesk', array(__CLASS__, 'dashboard'), 'dashicons-archive', 26);
        add_submenu_page('stockdesk', 'Overview', 'Overview', 'manage_options', 'stockdesk', array(__CLASS__, 'dashboard'));
        add_submenu_page('stockdesk', 'Products', 'Products', 'manage_options', 'stockdesk-products', array(__CLASS__, 'products'));
        add_submenu_page('stockdesk', 'Suppliers', 'Suppliers', 'manage_options', 'stockdesk-suppliers', array(__CLASS__, 'suppliers'));
        add_submenu_page('stockdesk', 'Stock Adjustment', 'Stock Adjustment', 'manage_options', 'stockdesk-stock', array(__CLASS__, 'stock'));
        add_submenu_page('stockdesk', 'Stock History', 'Stock History', 'manage_options', 'stockdesk-history', array(__CLASS__, 'history'));
        add_submenu_page('stockdesk', 'Low Stock', 'Low Stock', 'manage_options', 'stockdesk-low-stock', array(__CLASS__, 'low_stock'));
    }

    public static function assets($hook) {
        if (strpos($hook, 'stockdesk') === false) {
            return;
        }

        wp_enqueue_style('stockdesk-admin', STOCKDESK_URL . 'assets/css/admin.css', array(), STOCKDESK_VERSION);
    }

    public static function dashboard() {
        global $wpdb;
        $products = $wpdb->prefix . 'stockdesk_products';
        $log      = $wpdb->prefix . 'stockdesk_stock_log';

        $counts = array(
            'Total Products' => (int) $wpdb->get_var("SELECT COUNT(*) FROM $products"),
            'In Stock'       => (int) $wpdb->get_var("SELECT COUNT(*) FROM $products WHERE stock > low_stock_threshold"),
            'Low Stock'      => (int) $wpdb->get_var("SELECT COUNT(*) FROM $products WHERE stock > 0 AND stock <= low_stock_threshold"),
            'Out of Stock'   => (int) $wpdb->get_var("SELECT COUNT(*) FROM $products WHERE stock <= 0"),
        );

        $logs = $wpdb->get_results(
            "SELECT l.*, p.name FROM $log l
             LEFT JOIN $products p ON p.id = l.product_id
             ORDER BY l.id DESC LIMIT 8"
        );

        include STOCKDESK_PATH . 'views/dashboard.php';
    }

    public static function products() {
        global $wpdb;

        $show_form = isset($_GET['add']);
        $suppliers = $show_form ? $wpdb->get_results("SELECT id, name FROM {$wpdb->prefix}stockdesk_suppliers ORDER BY name") : array();
        $products  = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}stockdesk_products ORDER BY id DESC");

        include STOCKDESK_PATH . 'views/products.php';
    }

    public static function suppliers() {
        global $wpdb;

        $show_form = isset($_GET['add']);
        $suppliers = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}stockdesk_suppliers ORDER BY id DESC");

        include STOCKDESK_PATH . 'views/suppliers.php';
    }

    public static function stock() {
        global $wpdb;

        $products = $wpdb->get_results("SELECT id, name, sku, stock FROM {$wpdb->prefix}stockdesk_products ORDER BY name");

        include STOCKDESK_PATH . 'views/stock.php';
    }

    public static function history() {
        global $wpdb;

        $logs = $wpdb->get_results(
            "SELECT l.*, p.name FROM {$wpdb->prefix}stockdesk_stock_log l
             LEFT JOIN {$wpdb->prefix}stockdesk_products p ON p.id = l.product_id
             ORDER BY l.id DESC"
        );

        include STOCKDESK_PATH . 'views/history.php';
    }

    public static function low_stock() {
        global $wpdb;

        $products = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}stockdesk_products
             WHERE stock > 0 AND stock <= low_stock_threshold
             ORDER BY stock ASC"
        );

        include STOCKDESK_PATH . 'views/low-stock.php';
    }

    public static function render_notices() {
        $errors = array(
            'required'      => 'Please fill in the required fields.',
            'duplicate_sku' => 'A product with this SKU already exists.',
            'invalid'       => 'Please select a product and enter a valid quantity.',
            'insufficient'  => 'There is not enough stock for this stock out.',
            'db'            => 'Could not save. Please try again.',
        );

        if (isset($_GET['saved'])) {
            echo '<div class="notice notice-success is-dismissible"><p>Saved.</p></div>';
        }

        $error = isset($_GET['error']) ? sanitize_key($_GET['error']) : '';
        if ($error) {
            $message = $errors[$error] ?? $errors['db'];
            echo '<div class="notice notice-error is-dismissible"><p>' . esc_html($message) . '</p></div>';
        }
    }

    public static function stock_status($product) {
        if ($product->stock <= 0) {
            return 'Out of Stock';
        }

        return $product->stock <= $product->low_stock_threshold ? 'Low Stock' : 'In Stock';
    }

    public static function format_change($quantity) {
        return $quantity > 0 ? '+' . $quantity : (string) $quantity;
    }
}
