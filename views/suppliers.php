<?php if (!defined('ABSPATH')) exit; ?>
<div class="wrap stockdesk">
    <h1>Suppliers</h1>
    <?php StockDesk_Admin::render_notices(); ?>

    <p>
        <a class="button button-primary" href="<?php echo esc_url(admin_url('admin.php?page=stockdesk-suppliers&add=1')); ?>">Add Supplier</a>
    </p>

    <?php if ($show_form) : ?>
        <div class="stockdesk-panel">
            <h2>Add Supplier</h2>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php wp_nonce_field('stockdesk_save_supplier'); ?>
                <input type="hidden" name="action" value="stockdesk_save_supplier">

                <table class="form-table">
                    <tr>
                        <th><label for="stockdesk-supplier-name">Supplier Name</label></th>
                        <td><input id="stockdesk-supplier-name" class="regular-text" type="text" name="name" required></td>
                    </tr>
                    <tr>
                        <th><label for="stockdesk-contact">Contact Person</label></th>
                        <td><input id="stockdesk-contact" class="regular-text" type="text" name="contact_person"></td>
                    </tr>
                    <tr>
                        <th><label for="stockdesk-phone">Phone</label></th>
                        <td><input id="stockdesk-phone" class="regular-text" type="text" name="phone"></td>
                    </tr>
                    <tr>
                        <th><label for="stockdesk-email">Email</label></th>
                        <td><input id="stockdesk-email" class="regular-text" type="email" name="email"></td>
                    </tr>
                    <tr>
                        <th><label for="stockdesk-address">Address</label></th>
                        <td><textarea id="stockdesk-address" class="large-text" name="address"></textarea></td>
                    </tr>
                    <tr>
                        <th><label for="stockdesk-notes">Notes</label></th>
                        <td><textarea id="stockdesk-notes" class="large-text" name="notes"></textarea></td>
                    </tr>
                </table>

                <p><button type="submit" class="button button-primary">Save Supplier</button></p>
            </form>
        </div>
    <?php endif; ?>

    <div class="stockdesk-panel">
        <?php if (!$suppliers) : ?>
            <p>No suppliers yet.</p>
        <?php else : ?>
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($suppliers as $supplier) : ?>
                        <?php
                        $delete_url = wp_nonce_url(
                            admin_url('admin-post.php?action=stockdesk_delete_supplier&id=' . $supplier->id),
                            'stockdesk_delete_supplier_' . $supplier->id
                        );
                        ?>
                        <tr>
                            <td><?php echo esc_html($supplier->name); ?></td>
                            <td><?php echo esc_html($supplier->contact_person); ?></td>
                            <td><?php echo esc_html($supplier->phone); ?></td>
                            <td><?php echo esc_html($supplier->email); ?></td>
                            <td><a href="<?php echo esc_url($delete_url); ?>" onclick="return confirm('Delete this supplier?');">Delete</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
