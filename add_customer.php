<?php
  $page_title = 'Add Customer';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
 if(isset($_POST['add_customer'])){
   $req_fields = array('customer-name','customer-address','customer-city','customer-province', 'customer-zipcode', 'customer-telephone');
   validate_fields($req_fields);
   if(empty($errors)){
     $c_name  = remove_junk($db->escape($_POST['customer-name']));
     $c_address = remove_junk($db->escape($_POST['customer-address']));
     $c_city  = remove_junk($db->escape($_POST['customer-city']));
     $c_province = remove_junk($db->escape($_POST['customer-province']));
     $c_zipcode = remove_junk($db->escape($_POST['customer-zipcode']));
     $c_telephone = remove_junk($db->escape($_POST['customer-telephone']));
     $c_email = '';
     if(isset($_POST['customer-email']) && !empty($_POST['customer-email'])) {
       $c_email = remove_junk($db->escape($_POST['customer-email']));
     }
     
     $date = make_date();
     $query  = "INSERT INTO customers (";
     $query .=" customer_name,address,city,province,zip_code,telephone,email,date_added";
     $query .=") VALUES (";
     $query .=" '{$c_name}', '{$c_address}', '{$c_city}', '{$c_province}', '{$c_zipcode}', '{$c_telephone}', '{$c_email}', '{$date}'";
     $query .=")";
     $query .=" ON DUPLICATE KEY UPDATE customer_name='{$c_name}'";
     if($db->query($query)){
       $session->msg('s',"Customer added successfully");
       redirect('add_customer.php', false);
     } else {
       $session->msg('d',' Sorry failed to add customer!');
       redirect('customers.php', false);
     }

   } else{
     $session->msg("d", $errors);
     redirect('add_customer.php',false);
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
            <span class="glyphicon glyphicon-user"></span>
            <span>Add New Customer</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="add_customer.php" class="clearfix">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-user"></i>
                  </span>
                  <input type="text" class="form-control" name="customer-name" placeholder="Customer Name">
               </div>
              </div>
              
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-home"></i>
                  </span>
                  <input type="text" class="form-control" name="customer-address" placeholder="Address">
               </div>
              </div>
              
              <div class="form-group">
               <div class="row">
                 <div class="col-md-6">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-map-marker"></i>
                     </span>
                     <input type="text" class="form-control" name="customer-city" placeholder="City">
                  </div>
                 </div>
                 <div class="col-md-6">
                   <div class="input-group">
                     <span class="input-group-addon">
                       <i class="glyphicon glyphicon-map-marker"></i>
                     </span>
                     <input type="text" class="form-control" name="customer-province" placeholder="Province">
                  </div>
                 </div>
               </div>
              </div>

              <div class="form-group">
               <div class="row">
                 <div class="col-md-6">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-envelope"></i>
                     </span>
                     <input type="text" class="form-control" name="customer-zipcode" placeholder="Zip Code">
                  </div>
                 </div>
                 <div class="col-md-6">
                   <div class="input-group">
                     <span class="input-group-addon">
                       <i class="glyphicon glyphicon-phone"></i>
                     </span>
                     <input type="text" class="form-control" name="customer-telephone" placeholder="Telephone Number">
                  </div>
                 </div>
               </div>
              </div>
              
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-envelope"></i>
                  </span>
                  <input type="email" class="form-control" name="customer-email" placeholder="Email Address (Optional)">
               </div>
              </div>

              <button type="submit" name="add_customer" class="btn btn-danger">Add Customer</button>
          </form>
         </div>
        </div>
      </div>
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>