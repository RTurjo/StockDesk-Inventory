<?php
if (!defined('ABSPATH')) {
    exit;
}

class StockDesk_Suppliers {
    public static function init() {
        add_action('admin_post_stockdesk_save_supplier', array(__CLASS__, 'save'));
        add_action('admin_post_stockdesk_delete_supplier', array(__CLASS__, 'delete'));
    }

    public static function save() {
        if (!current_user_can('manage_options')) {
            wp_die('You are not allowed to do this.');
        }
        check_admin_referer('stockdesk_save_supplier');

        global $wpdb;

        $data = array(
            'name'           => sanitize_text_field(wp_unslash($_POST['name'] ?? '')),
            'contact_person' => sanitize_text_field(wp_unslash($_POST['contact_person'] ?? '')),
            'phone'          => sanitize_text_field(wp_unslash($_POST['phone'] ?? '')),
            'email'          => sanitize_email(wp_unslash($_POST['email'] ?? '')),
            'address'        => sanitize_textarea_field(wp_unslash($_POST['address'] ?? '')),
            'notes'          => sanitize_textarea_field(wp_unslash($_POST['notes'] ?? '')),
            'created_at'     => current_time('mysql'),
        );

        if ($data['name'] === '') {
            self::redirect('required');
        }

        $inserted = $wpdb->insert($wpdb->prefix . 'stockdesk_suppliers', $data, array('%s', '%s', '%s', '%s', '%s', '%s', '%s'));

        self::redirect($inserted ? '' : 'db');
    }

    public static function delete() {
        if (!current_user_can('manage_options')) {
            wp_die('You are not allowed to do this.');
        }

        $id = absint($_GET['id'] ?? 0);
        check_admin_referer('stockdesk_delete_supplier_' . $id);

        global $wpdb;
        $wpdb->update(
            $wpdb->prefix . 'stockdesk_products',
            array('supplier_id' => 0),
            array('supplier_id' => $id),
            array('%d'),
            array('%d')
        );
        $wpdb->delete($wpdb->prefix . 'stockdesk_suppliers', array('id' => $id), array('%d'));

        wp_safe_redirect(admin_url('admin.php?page=stockdesk-suppliers'));
        exit;
    }

    private static function redirect($error = '') {
        $args = $error ? array('error' => $error) : array('saved' => 1);
        wp_safe_redirect(add_query_arg($args, admin_url('admin.php?page=stockdesk-suppliers')));
        exit;
    }
}
