<?php
  $page_title = 'Sales Returns';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
?>

<?php
// Get all sales returns
$all_returns = find_all_returns();
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Sales Returns History</span>
        </strong>
        <div class="pull-right">
          <a href="return_sale.php" class="btn btn-primary">Process New Return</a>
        </div>
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Sale ID</th>
              <th>Product</th>
              <th>Qty</th>
              <th>Price</th>
              <th>Total</th>
              <th>Date</th>
              <th>Condition</th>
              <th>Reason</th>
              <th>Returned By</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all_returns as $return):?>
            <tr>
              <td class="text-center"><?php echo count_id();?></td>
              <td><?php echo remove_junk($return['sale_id']); ?></td>
              <td><?php echo remove_junk($return['product_name']); ?></td>
              <td><?php echo (int)$return['quantity']; ?></td>
              <td><?php echo remove_junk($return['price']); ?></td>
              <td><?php echo remove_junk($return['price'] * $return['quantity']); ?></td>
              <td><?php echo date("d/m/Y", strtotime($return['return_date'])); ?></td>
              <td><?php echo remove_junk(ucfirst($return['return_condition'])); ?></td>
              <td><?php echo remove_junk($return['reason']); ?></td>
              <td><?php echo remove_junk($return['username']); ?></td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>