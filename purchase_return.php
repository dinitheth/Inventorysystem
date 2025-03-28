<?php
  $page_title = 'Purchase Return';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
  $all_products = find_all('products');
?>
<?php
 if(isset($_POST['add_return'])){
   $req_fields = array('product-id','return-quantity','return-reason');
   validate_fields($req_fields);
   if(empty($errors)){
     $p_id      = remove_junk($db->escape($_POST['product-id']));
     $r_qty     = remove_junk($db->escape($_POST['return-quantity']));
     $reason    = remove_junk($db->escape($_POST['return-reason']));
     $date      = make_date();
     
     // First, check if the product exists and has enough quantity
     $product = find_by_id('products', $p_id);
     
     if($product) {
       if($r_qty <= $product['quantity']) {
         // Create return record
         $query  = "INSERT INTO purchase_returns (";
         $query .=" product_id, return_quantity, return_date, reason";
         $query .=") VALUES (";
         $query .=" '{$p_id}', '{$r_qty}', '{$date}', '{$reason}'";
         $query .=")";
         
         if($db->query($query)){
           // Update product quantity (deduct returned items)
           $update_qty = $product['quantity'] - $r_qty;
           $query_update = "UPDATE products SET quantity = '{$update_qty}' WHERE id = '{$p_id}'";
           $result = $db->query($query_update);
           
           if($result) {
             $session->msg('s',"Purchase return processed successfully.");
             redirect('purchase_return.php', false);
           } else {
             $session->msg('d',"Failed to update product quantity.");
             redirect('purchase_return.php', false);
           }
         } else {
           $session->msg('d',"Failed to record purchase return.");
           redirect('purchase_return.php', false);
         }
       } else {
         $session->msg('d',"Return quantity exceeds available product quantity.");
         redirect('purchase_return.php', false);
       }
     } else {
       $session->msg('d',"Product not found.");
       redirect('purchase_return.php', false);
     }
   } else {
     $session->msg("d", $errors);
     redirect('purchase_return.php', false);
   }
 }
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Purchase Return</span>
        </strong>
      </div>
      <div class="panel-body">
        <div class="col-md-12">
          <form method="post" action="purchase_return.php" class="clearfix">
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-th-large"></i>
                </span>
                <select class="form-control" name="product-id" required>
                  <option value="">Select Product</option>
                  <?php foreach ($all_products as $prod): ?>
                    <option value="<?php echo (int)$prod['id'] ?>">
                      <?php echo $prod['name'].' (In Stock: '.$prod['quantity'].')' ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-shopping-cart"></i>
                </span>
                <input type="number" class="form-control" name="return-quantity" placeholder="Return Quantity" required>
              </div>
            </div>
            
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-comment"></i>
                </span>
                <textarea class="form-control" name="return-reason" placeholder="Reason for Return" rows="3" required></textarea>
              </div>
            </div>
            
            <button type="submit" name="add_return" class="btn btn-danger">Process Return</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-info-sign"></span>
          <span>Return Instructions</span>
        </strong>
      </div>
      <div class="panel-body">
        <p>1. Select the product being returned.</p>
        <p>2. Enter the quantity being returned.</p>
        <p>3. Provide a reason for the return.</p>
        <p>4. The system will automatically deduct the returned quantity from inventory.</p>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>