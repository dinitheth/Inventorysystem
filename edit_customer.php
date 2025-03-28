<?php
  $page_title = 'Edit Customer';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
?>
<?php
// Custom function to find customer by customer_id
function find_customer_by_id($id) {
  global $db;
  $id = (int)$id;
  $sql = "SELECT * FROM customers WHERE customer_id={$id} LIMIT 1";
  $result = $db->query($sql);
  return ($result && $db->num_rows($result) === 1) ? $db->fetch_assoc($result) : false;
}

$customer = find_customer_by_id((int)$_GET['id']);
if(!$customer){
  $session->msg("d","Missing customer id.");
  redirect('customers.php');
}
?>
<?php
  if(isset($_POST['update_customer'])){
    $req_fields = array('customer_name','address','city','province','zip_code','telephone');
    validate_fields($req_fields);

    if(empty($errors)){
      $c_id    = (int)$customer['customer_id'];
      $c_name  = remove_junk($db->escape($_POST['customer_name']));
      $c_address = remove_junk($db->escape($_POST['address']));
      $c_city  = remove_junk($db->escape($_POST['city']));
      $c_province = remove_junk($db->escape($_POST['province']));
      $c_zip   = remove_junk($db->escape($_POST['zip_code']));
      $c_tel   = remove_junk($db->escape($_POST['telephone']));
      $c_email = $_POST['email'] != '' ? remove_junk($db->escape($_POST['email'])) : NULL;

      $sql  = "UPDATE customers SET";
      $sql .= " customer_name='{$c_name}', address='{$c_address}',";
      $sql .= " city='{$c_city}', province='{$c_province}', zip_code='{$c_zip}',";
      $sql .= " telephone='{$c_tel}', email=" . ($c_email ? "'{$c_email}'" : "NULL");
      $sql .= " WHERE customer_id='{$c_id}'";
      
      $result = $db->query($sql);
      if($result && $db->affected_rows() === 1){
        $session->msg('s',"Customer updated successfully.");
        redirect('edit_customer.php?id='.$c_id, false);
      } else {
        $session->msg('d','Sorry, failed to update customer!');
        redirect('customers.php', false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('edit_customer.php?id='.(int)$customer['customer_id'], false);
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
  <div class="col-md-12">
    <div class="panel">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-user"></span>
          <span>Edit Customer</span>
        </strong>
        <div class="pull-right">
          <a href="customers_list.php" class="btn btn-primary">Show all customers</a>
        </div>
      </div>
      <div class="panel-body">
        <form method="post" action="edit_customer.php?id=<?php echo (int)$customer['customer_id']; ?>" class="clearfix">
          <div class="form-group">
            <label for="customer_name" class="control-label">Customer Name</label>
            <input type="text" class="form-control" name="customer_name" value="<?php echo remove_junk($customer['customer_name']); ?>" required>
          </div>
          
          <div class="form-group">
            <label for="address" class="control-label">Address</label>
            <input type="text" class="form-control" name="address" value="<?php echo remove_junk($customer['address']); ?>" required>
          </div>
          
          <div class="form-group">
            <div class="row">
              <div class="col-md-4">
                <label for="city" class="control-label">City</label>
                <input type="text" class="form-control" name="city" value="<?php echo remove_junk($customer['city']); ?>" required>
              </div>
              <div class="col-md-4">
                <label for="province" class="control-label">Province</label>
                <input type="text" class="form-control" name="province" value="<?php echo remove_junk($customer['province']); ?>" required>
              </div>
              <div class="col-md-4">
                <label for="zip_code" class="control-label">Zip Code</label>
                <input type="text" class="form-control" name="zip_code" value="<?php echo remove_junk($customer['zip_code']); ?>" required>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <div class="row">
              <div class="col-md-6">
                <label for="telephone" class="control-label">Telephone</label>
                <input type="text" class="form-control" name="telephone" value="<?php echo remove_junk($customer['telephone']); ?>" required>
              </div>
              <div class="col-md-6">
                <label for="email" class="control-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?php echo $customer['email'] ? remove_junk($customer['email']) : ''; ?>">
              </div>
            </div>
          </div>
          
          <div class="form-group text-right">
            <button type="submit" name="update_customer" class="btn btn-primary">Update Customer</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>