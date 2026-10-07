<?php
/**
 * Plugin Name: StockDesk Inventory
 * Description: Track products, suppliers and stock movements from the WordPress admin.
 * Version: 1.0.0
 * Author: StockDesk
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

if (!defined('ABSPATH')) {
    exit;
}

define('STOCKDESK_VERSION', '1.0.0');
define('STOCKDESK_PATH', plugin_dir_path(__FILE__));
define('STOCKDESK_URL', plugin_dir_url(__FILE__));

require_once STOCKDESK_PATH . 'includes/class-stockdesk-db.php';
require_once STOCKDESK_PATH . 'includes/class-stockdesk-products.php';
require_once STOCKDESK_PATH . 'includes/class-stockdesk-suppliers.php';
require_once STOCKDESK_PATH . 'includes/class-stockdesk-stock.php';
require_once STOCKDESK_PATH . 'includes/class-stockdesk-admin.php';

register_activation_hook(__FILE__, array('StockDesk_DB', 'activate'));

add_action('plugins_loaded', function () {
    StockDesk_Products::init();
    StockDesk_Suppliers::init();
    StockDesk_Stock::init();
    StockDesk_Admin::init();
});
