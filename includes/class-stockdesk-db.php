<?php
if (!defined('ABSPATH')) {
    exit;
}

class StockDesk_DB {
    public static function activate() {
        global $wpdb;

        $charset   = $wpdb->get_charset_collate();
        $products  = $wpdb->prefix . 'stockdesk_products';
        $suppliers = $wpdb->prefix . 'stockdesk_suppliers';
        $stock_log = $wpdb->prefix . 'stockdesk_stock_log';

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        // dbDelta needs two spaces after PRIMARY KEY.
        dbDelta("CREATE TABLE $products (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            name varchar(190) NOT NULL,
            sku varchar(100) NOT NULL,
            category varchar(190) DEFAULT '',
            supplier_id bigint(20) unsigned DEFAULT 0,
            purchase_price decimal(12,2) DEFAULT 0,
            selling_price decimal(12,2) DEFAULT 0,
            stock int(11) DEFAULT 0,
            low_stock_threshold int(11) DEFAULT 5,
            description text,
            created_at datetime NOT NULL,
            PRIMARY KEY  (id),
            UNIQUE KEY sku (sku)
        ) $charset;");

        dbDelta("CREATE TABLE $suppliers (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            name varchar(190) NOT NULL,
            contact_person varchar(190) DEFAULT '',
            phone varchar(50) DEFAULT '',
            email varchar(190) DEFAULT '',
            address text,
            notes text,
            created_at datetime NOT NULL,
            PRIMARY KEY  (id)
        ) $charset;");

        dbDelta("CREATE TABLE $stock_log (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            product_id bigint(20) unsigned NOT NULL,
            type varchar(20) NOT NULL,
            quantity int(11) NOT NULL,
            reason varchar(190) DEFAULT '',
            notes text,
            created_at datetime NOT NULL,
            PRIMARY KEY  (id),
            KEY product_id (product_id)
        ) $charset;");
    }
}
