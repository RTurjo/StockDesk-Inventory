<?php
if (!defined('ABSPATH')) {
    exit;
}

class StockDesk_Products {
    public static function init() {
        add_action('admin_post_stockdesk_save_product', array(__CLASS__, 'save'));
        add_action('admin_post_stockdesk_delete_product', array(__CLASS__, 'delete'));
    }

    public static function save() {
        if (!current_user_can('manage_options')) {
            wp_die('You are not allowed to do this.');
        }
        check_admin_referer('stockdesk_save_product');

        global $wpdb;
        $table = $wpdb->prefix . 'stockdesk_products';

        $data = array(
            'name'                => sanitize_text_field(wp_unslash($_POST['name'] ?? '')),
            'sku'                 => sanitize_text_field(wp_unslash($_POST['sku'] ?? '')),
            'category'            => sanitize_text_field(wp_unslash($_POST['category'] ?? '')),
            'supplier_id'         => absint($_POST['supplier_id'] ?? 0),
            'purchase_price'      => max(0, (float) ($_POST['purchase_price'] ?? 0)),
            'selling_price'       => max(0, (float) ($_POST['selling_price'] ?? 0)),
            'stock'               => self::int_field('stock', 0),
            'low_stock_threshold' => self::int_field('low_stock_threshold', 5),
            'description'         => sanitize_textarea_field(wp_unslash($_POST['description'] ?? '')),
            'created_at'          => current_time('mysql'),
        );

        if ($data['name'] === '' || $data['sku'] === '') {
            self::redirect('required');
        }

        $sku_taken = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE sku = %s", $data['sku']));
        if ($sku_taken) {
            self::redirect('duplicate_sku');
        }

        $inserted = $wpdb->insert($table, $data, array('%s', '%s', '%s', '%d', '%f', '%f', '%d', '%d', '%s', '%s'));

        self::redirect($inserted ? '' : 'db');
    }

    public static function delete() {
        if (!current_user_can('manage_options')) {
            wp_die('You are not allowed to do this.');
        }

        $id = absint($_GET['id'] ?? 0);
        check_admin_referer('stockdesk_delete_product_' . $id);

        global $wpdb;
        $wpdb->delete($wpdb->prefix . 'stockdesk_stock_log', array('product_id' => $id), array('%d'));
        $wpdb->delete($wpdb->prefix . 'stockdesk_products', array('id' => $id), array('%d'));

        wp_safe_redirect(admin_url('admin.php?page=stockdesk-products'));
        exit;
    }

    private static function int_field($key, $default) {
        if (!isset($_POST[$key]) || $_POST[$key] === '') {
            return $default;
        }

        return max(0, intval($_POST[$key]));
    }

    private static function redirect($error = '') {
        $args = $error ? array('error' => $error) : array('saved' => 1);
        wp_safe_redirect(add_query_arg($args, admin_url('admin.php?page=stockdesk-products')));
        exit;
    }
}
