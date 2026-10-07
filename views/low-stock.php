<?php if (!defined('ABSPATH')) exit; ?>
<div class="wrap stockdesk">
    <h1>Low Stock</h1>

    <div class="stockdesk-panel">
        <?php if (!$products) : ?>
            <p>No products are low on stock.</p>
        <?php else : ?>
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Current Stock</th>
                        <th>Threshold</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product) : ?>
                        <tr>
                            <td><?php echo esc_html($product->name); ?></td>
                            <td><?php echo esc_html($product->sku); ?></td>
                            <td><?php echo esc_html($product->stock); ?></td>
                            <td><?php echo esc_html($product->low_stock_threshold); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
