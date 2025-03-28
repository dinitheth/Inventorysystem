<?php
$page_title = 'Stock Notifications';
require_once('includes/load.php');
page_require_level(2);
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-bell"></span>
                    <span>Stock Notifications</span>
                </strong>
            </div>
            <div class="panel-body">
                <?php 
                $low_stock_items = get_low_stock_items();
                if (!empty($low_stock_items)): ?>
                    <div class="alert alert-warning">
                        <h4><i class="glyphicon glyphicon-warning-sign"></i> Low Stock Alerts</h4>
                        <ul class="list-unstyled">
                            <?php foreach($low_stock_items as $item): ?>
                                <li class="stock-alert-item">
                                    <strong><?php echo remove_junk($item['name']); ?></strong>
                                    - Current Stock: <span class="text-danger"><?php echo $item['quantity']; ?></span>
                                    (Minimum Required: <?php echo $item['minimum_quantity']; ?>)
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php else: ?>
                    <div class="alert alert-success">
                        <i class="glyphicon glyphicon-check"></i>
                        All stock levels are normal. No alerts at this time.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.stock-alert-item {
    padding: 10px;
    margin-bottom: 5px;
    border-bottom: 10px solid whitesmoke;
}
.stock-alert-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
}
</style>

<?php include_once('layouts/footer.php'); ?>