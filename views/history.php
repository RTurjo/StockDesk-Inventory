<?php if (!defined('ABSPATH')) exit; ?>
<div class="wrap stockdesk">
    <h1>Stock History</h1>

    <div class="stockdesk-panel">
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
                        <th>Notes</th>
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
                            <td><?php echo esc_html($log->notes); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
