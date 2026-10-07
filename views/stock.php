<?php if (!defined('ABSPATH')) exit; ?>
<div class="wrap stockdesk">
    <h1>Stock Adjustment</h1>
    <?php StockDesk_Admin::render_notices(); ?>

    <div class="stockdesk-panel">
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('stockdesk_adjust_stock'); ?>
            <input type="hidden" name="action" value="stockdesk_adjust_stock">

            <table class="form-table">
                <tr>
                    <th><label for="stockdesk-product">Product</label></th>
                    <td>
                        <select id="stockdesk-product" name="product_id" required>
                            <option value="">Select product</option>
                            <?php foreach ($products as $product) : ?>
                                <option value="<?php echo esc_attr($product->id); ?>">
                                    <?php echo esc_html(sprintf('%s (%s, stock: %d)', $product->name, $product->sku, $product->stock)); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="stockdesk-type">Adjustment Type</label></th>
                    <td>
                        <select id="stockdesk-type" name="type">
                            <option value="in">Stock In</option>
                            <option value="out">Stock Out</option>
                            <option value="adjustment">Set Stock</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="stockdesk-quantity">Quantity</label></th>
                    <td><input id="stockdesk-quantity" type="number" name="quantity" min="0" required></td>
                </tr>
                <tr>
                    <th><label for="stockdesk-reason">Reason</label></th>
                    <td><input id="stockdesk-reason" class="regular-text" type="text" name="reason"></td>
                </tr>
                <tr>
                    <th><label for="stockdesk-stock-notes">Notes</label></th>
                    <td><textarea id="stockdesk-stock-notes" class="large-text" name="notes"></textarea></td>
                </tr>
            </table>

            <p><button type="submit" class="button button-primary">Update Stock</button></p>
        </form>
    </div>
</div>
