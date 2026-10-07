<?php if (!defined('ABSPATH')) exit; ?>
<div class="wrap stockdesk">
    <h1>Inventory Overview</h1>

    <div class="stockdesk-cards">
        <?php foreach ($counts as $label => $count) : ?>
            <div class="stockdesk-card">
                <span><?php echo esc_html($label); ?></span>
                <strong><?php echo esc_html($count); ?></strong>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="stockdesk-panel">
        <h2>Recent Stock Activity</h2>

        <?php if (!$logs) : ?>
            <p>No stock activity yet.</p>
        <?php else : ?>
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Type</th>
                        <th>Change</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log) : ?>
                        <tr>
                            <td><?php echo esc_html($log->created_at); ?></td>
                            <td><?php echo esc_html($log->name); ?></td>
                            <td><?php echo esc_html(ucfirst($log->type)); ?></td>
                            <td><?php echo esc_html(StockDesk_Admin::format_change($log->quantity)); ?></td>
                            <td><?php echo esc_html($log->reason); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
