<?php if (!defined('ABSPATH')) exit; ?>
<div class="wrap stockdesk">
    <h1>Products</h1>
    <?php StockDesk_Admin::render_notices(); ?>

    <p>
        <a class="button button-primary" href="<?php echo esc_url(admin_url('admin.php?page=stockdesk-products&add=1')); ?>">Add Product</a>
    </p>

    <?php if ($show_form) : ?>
        <div class="stockdesk-panel">
            <h2>Add Product</h2>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php wp_nonce_field('stockdesk_save_product'); ?>
                <input type="hidden" name="action" value="stockdesk_save_product">

                <table class="form-table">
                    <tr>
                        <th><label for="stockdesk-name">Product Name</label></th>
                        <td><input id="stockdesk-name" class="regular-text" type="text" name="name" required></td>
                    </tr>
                    <tr>
                        <th><label for="stockdesk-sku">SKU</label></th>
                        <td><input id="stockdesk-sku" class="regular-text" type="text" name="sku" required></td>
                    </tr>
                    <tr>
                        <th><label for="stockdesk-category">Category</label></th>
                        <td><input id="stockdesk-category" class="regular-text" type="text" name="category"></td>
                    </tr>
                    <tr>
                        <th><label for="stockdesk-purchase-price">Purchase Price</label></th>
                        <td><input id="stockdesk-purchase-price" type="number" name="purchase_price" step="0.01" min="0"></td>
                    </tr>
                    <tr>
                        <th><label for="stockdesk-selling-price">Selling Price</label></th>
                        <td><input id="stockdesk-selling-price" type="number" name="selling_price" step="0.01" min="0"></td>
                    </tr>
                    <tr>
                        <th><label for="stockdesk-stock">Current Stock</label></th>
                        <td><input id="stockdesk-stock" type="number" name="stock" min="0" value="0"></td>
                    </tr>
                    <tr>
                        <th><label for="stockdesk-threshold">Low Stock Threshold</label></th>
                        <td><input id="stockdesk-threshold" type="number" name="low_stock_threshold" min="0" value="5"></td>
                    </tr>
                    <tr>
                        <th><label for="stockdesk-supplier">Supplier</label></th>
                        <td>
                            <select id="stockdesk-supplier" name="supplier_id">
                                <option value="0">Select supplier</option>
                                <?php foreach ($suppliers as $supplier) : ?>
                                    <option value="<?php echo esc_attr($supplier->id); ?>"><?php echo esc_html($supplier->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="stockdesk-description">Description</label></th>
                        <td><textarea id="stockdesk-description" class="large-text" name="description" rows="4"></textarea></td>
                    </tr>
                </table>

                <p><button type="submit" class="button button-primary">Save Product</button></p>
            </form>
        </div>
    <?php endif; ?>

    <div class="stockdesk-panel">
        <?php if (!$products) : ?>
            <p>No products yet.</p>
        <?php else : ?>
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product) : ?>
                        <?php
                        $delete_url = wp_nonce_url(
                            admin_url('admin-post.php?action=stockdesk_delete_product&id=' . $product->id),
                            'stockdesk_delete_product_' . $product->id
                        );
                        ?>
                        <tr>
                            <td><?php echo esc_html($product->name); ?></td>
                            <td><?php echo esc_html($product->sku); ?></td>
                            <td><?php echo esc_html($product->category); ?></td>
                            <td><?php echo esc_html($product->stock); ?></td>
                            <td><?php echo esc_html(StockDesk_Admin::stock_status($product)); ?></td>
                            <td><a href="<?php echo esc_url($delete_url); ?>" onclick="return confirm('Delete this product and its stock history?');">Delete</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
