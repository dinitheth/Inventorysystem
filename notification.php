<?php
$page_title = 'Stock Notifications';
require_once('includes/load.php');
page_require_level(3);
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-bell"></span>
                    <span>Stock Notifications</span>
                </strong>
                <span class="pull-right">
                    <a href="add_product.php" class="btn btn-primary btn-xs">
                        <span class="glyphicon glyphicon-plus"></span> Add Product
                    </a>
                </span>
            </div>
            <div class="panel-body">
                <?php 
                // Modified function call to include product IDs
                $sql = "SELECT id, name, quantity, minimum_quantity FROM products WHERE quantity <= minimum_quantity ORDER BY quantity ASC";
                $low_stock_items = find_by_sql($sql);
                
                if (!empty($low_stock_items)): ?>
                    <div class="dashboard-summary">
                        <div class="alert alert-warning summary-box">
                            <h4><i class="glyphicon glyphicon-warning-sign"></i> Low Stock Summary</h4>
                            <div class="summary-numbers">
                                <div class="summary-item">
                                    <span class="number"><?php echo count($low_stock_items); ?></span>
                                    <span class="text">Products Low</span>
                                </div>
                                <?php 
                                    // Calculate critical items (less than 50% of minimum)
                                    $critical_count = 0;
                                    foreach($low_stock_items as $item) {
                                        if ($item['quantity'] < ($item['minimum_quantity'] * 0.5)) {
                                            $critical_count++;
                                        }
                                    }
                                ?>
                                <div class="summary-item critical">
                                    <span class="number"><?php echo $critical_count; ?></span>
                                    <span class="text">Critical</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped stock-alert-table">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th class="text-center">Current Stock</th>
                                    <th class="text-center">Minimum Required</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($low_stock_items as $item): 
                                    // Calculate status level
                                    $percent = ($item['quantity'] / $item['minimum_quantity']) * 100;
                                    if ($percent < 30) {
                                        $status_class = "danger";
                                        $status_text = "Critical";
                                    } elseif ($percent < 70) {
                                        $status_class = "warning";
                                        $status_text = "Low";
                                    } else {
                                        $status_class = "info";
                                        $status_text = "Reorder";
                                    }
                                ?>
                                <tr>
                                    <td><strong><?php echo remove_junk($item['name']); ?></strong></td>
                                    <td class="text-center stock-level">
                                        <span class="badge bg-<?php echo $status_class; ?>">
                                            <?php echo $item['quantity']; ?>
                                        </span>
                                    </td>
                                    <td class="text-center"><?php echo $item['minimum_quantity']; ?></td>
                                    <td class="text-center">
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-<?php echo $status_class; ?>" 
                                                 role="progressbar" 
                                                 aria-valuenow="<?php echo $percent; ?>" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100" 
                                                 style="width: <?php echo $percent; ?>%">
                                                <span><?php echo $status_text; ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                    <div class="btn-group">
                                        <?php if(isset($item['id'])): ?>
                                   <!-- Changed to point to add_stock.php (or whatever your stock addition page is called) -->
                                   <a href="product.php?id=<?php echo (int)$item['id']; ?>&restock=true" 
                                   class="btn btn-success btn-xs" title="Restock">
                                   <i class="glyphicon glyphicon-shopping-cart"></i>
                                     </a>
                                       <a href="edit_product.php?id=<?php echo (int)$item['id']; ?>" 
                                          class="btn btn-primary btn-xs" title="Edit">
                                          <i class="glyphicon glyphicon-pencil"></i>
                                     </a>
                                    <?php else: ?>
                                   <a href="product.php" class="btn btn-info btn-xs" title="View All Products">
                                   <i class="glyphicon glyphicon-list"></i>
                                      </a>
                                    <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-success all-clear">
                        <i class="glyphicon glyphicon-check"></i>
                        <h4>All Stock Levels Normal</h4>
                        <p>Your inventory is in good shape. No low stock alerts at this time.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Dashboard Summary Section */
.dashboard-summary {
    margin-bottom: 20px;
}

.summary-box {
    border-radius: 4px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    padding: 15px;
    border-left: 5px solid #f0ad4e;
}

.summary-box h4 {
    margin-top: 0;
    color: #8a6d3b;
    font-weight: 600;
    margin-bottom: 15px;
    border-bottom: 1px solid rgba(138, 109, 59, 0.2);
    padding-bottom: 10px;
}

.summary-numbers {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
}

.summary-item {
    text-align: center;
    padding: 10px 15px;
    background-color: rgba(255, 255, 255, 0.6);
    border-radius: 4px;
    min-width: 100px;
}

.summary-item .number {
    display: block;
    font-size: 24px;
    font-weight: bold;
    color: #f0ad4e;
}

.summary-item .text {
    font-size: 12px;
    text-transform: uppercase;
    color: #8a6d3b;
}

.summary-item.critical .number {
    color: #d9534f;
}

/* Stock Alert Table */
.stock-alert-table {
    margin-top: 10px;
    border: 1px solid #ddd;
}

.stock-alert-table thead {
    background-color: #f5f5f5;
}

.stock-alert-table th {
    font-weight: 600;
}

.stock-level .badge {
    font-size: 14px;
    padding: 5px 10px;
}

.badge.bg-danger {
    background-color: #d9534f;
    color: #fff;
}

.badge.bg-warning {
    background-color: #f0ad4e;
    color: #fff;
}

.badge.bg-info {
    background-color: #5bc0de;
    color: #fff;
}

.progress {
    margin-bottom: 0;
    height: 20px;
}

.progress-bar {
    line-height: 20px;
}

/* All Clear Message */
.all-clear {
    text-align: center;
    padding: 30px;
    background-color: #dff0d8;
    border-color: #d6e9c6;
}

.all-clear i {
    font-size: 48px;
    color: #3c763d;
    margin-bottom: 15px;
    display: block;
}

.all-clear h4 {
    color: #3c763d;
    font-weight: 600;
    margin-bottom: 10px;
}

.all-clear p {
    color: #3c763d;
}

/* Responsive Design */
@media (max-width: 768px) {
    .summary-item {
        margin-bottom: 10px;
        width: 100%;
    }
    
    .stock-alert-table {
        font-size: 12px;
    }
    
    .btn-group .btn {
        padding: 3px 6px;
    }
    
    .progress {
        height: 15px;
    }
    
    .progress-bar {
        line-height: 15px;
        font-size: 10px;
    }
}
</style>

<?php include_once('layouts/footer.php'); ?>