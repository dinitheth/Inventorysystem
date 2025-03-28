<?php
  $page_title = 'Purchase Returns History';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  
  // Fetch all purchase returns with product details
  $sql = "SELECT r.id, r.return_quantity, r.return_date, r.reason, ";
  $sql .= "p.name AS product_name ";
  $sql .= "FROM purchase_returns r ";
  $sql .= "LEFT JOIN products p ON p.id = r.product_id ";
  $sql .= "ORDER BY r.return_date DESC";
  $returns = find_by_sql($sql);
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
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Purchase Returns History</span>
        </strong>
        <div class="pull-right">
          <a href="purchase_return.php" class="btn btn-primary">Add New Return</a>
        </div>
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Product</th>
              <th class="text-center" style="width: 15%;">Quantity</th>
              <th class="text-center" style="width: 15%;">Return Date</th>
              <th>Reason</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($returns as $return): ?>
            <tr>
              <td class="text-center"><?php echo count_id();?></td>
              <td><?php echo remove_junk($return['product_name']); ?></td>
              <td class="text-center"><?php echo remove_junk($return['return_quantity']); ?></td>
              <td class="text-center"><?php echo read_date($return['return_date']); ?></td>
              <td><?php echo remove_junk($return['reason']); ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>