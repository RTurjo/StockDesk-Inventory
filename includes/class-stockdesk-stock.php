<?php
if (!defined('ABSPATH')) {
    exit;
}

class StockDesk_Stock {
    public static function init() {
        add_action('admin_post_stockdesk_adjust_stock', array(__CLASS__, 'adjust'));
    }

    public static function adjust() {
        if (!current_user_can('manage_options')) {
            wp_die('You are not allowed to do this.');
        }
        check_admin_referer('stockdesk_adjust_stock');

        global $wpdb;
        $products = $wpdb->prefix . 'stockdesk_products';

        $product_id = absint($_POST['product_id'] ?? 0);
        $type       = sanitize_key(wp_unslash($_POST['type'] ?? ''));
        $qty        = absint($_POST['quantity'] ?? 0);
        $reason     = sanitize_text_field(wp_unslash($_POST['reason'] ?? ''));
        $notes      = sanitize_textarea_field(wp_unslash($_POST['notes'] ?? ''));

        $product = $wpdb->get_row($wpdb->prepare("SELECT id, stock FROM $products WHERE id = %d", $product_id));

        if (!$product || !in_array($type, array('in', 'out', 'adjustment'), true)) {
            self::redirect('invalid');
        }

        // Stock in/out need a positive quantity. "Set stock" may be zero.
        if ($type !== 'adjustment' && $qty < 1) {
            self::redirect('invalid');
        }

        if ($type === 'in') {
            $result = $wpdb->query($wpdb->prepare(
                "UPDATE $products SET stock = stock + %d WHERE id = %d",
                $qty,
                $product_id
            ));
            $change = $qty;
        } elseif ($type === 'out') {
            $result = $wpdb->query($wpdb->prepare(
                "UPDATE $products SET stock = stock - %d WHERE id = %d AND stock >= %d",
                $qty,
                $product_id,
                $qty
            ));
            if ($result === 0) {
                self::redirect('insufficient');
            }
            $change = -$qty;
        } else {
            $result = $wpdb->update($products, array('stock' => $qty), array('id' => $product_id), array('%d'), array('%d'));
            $change = $qty - (int) $product->stock;
        }

        if ($result === false) {
            self::redirect('db');
        }

        // The log stores the actual change in stock, positive or negative.
        $wpdb->insert(
            $wpdb->prefix . 'stockdesk_stock_log',
            array(
                'product_id' => $product_id,
                'type'       => $type,
                'quantity'   => $change,
                'reason'     => $reason,
                'notes'      => $notes,
                'created_at' => current_time('mysql'),
            ),
            array('%d', '%s', '%d', '%s', '%s', '%s')
        );

        self::redirect();
    }

    private static function redirect($error = '') {
        $args = $error ? array('error' => $error) : array('saved' => 1);
        wp_safe_redirect(add_query_arg($args, admin_url('admin.php?page=stockdesk-stock')));
        exit;
    }
}
