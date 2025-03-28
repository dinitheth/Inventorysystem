<?php
  $page_title = 'All Customers';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
  
  // Function to get all customers from the database
  function find_all_customers() {
    global $db;
    $sql = "SELECT * FROM customers ORDER BY customer_name ASC";
    return find_by_sql($sql);
  }
  
  $customers = find_all_customers();
?>
<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
         <div class="pull-right">
           <a href="add_customer.php" class="btn btn-primary">Add New</a>
         </div>
        </div>
        <div class="panel-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th> Customer Name </th>
                <th> Address </th>
                <th> City </th>
                <th> Province </th>
                <th> Zip Code </th>
                <th> Telephone </th>
                <th> Email </th>
                <th class="text-center" style="width: 10%;"> Date Added </th>
                <th class="text-center" style="width: 100px;"> Actions </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($customers as $customer):?>
              <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <td> <?php echo remove_junk($customer['customer_name']); ?></td>
                <td> <?php echo remove_junk($customer['address']); ?></td>
                <td> <?php echo remove_junk($customer['city']); ?></td>
                <td> <?php echo remove_junk($customer['province']); ?></td>
                <td> <?php echo remove_junk($customer['zip_code']); ?></td>
                <td> <?php echo remove_junk($customer['telephone']); ?></td>
                <td> <?php echo remove_junk($customer['email']); ?></td>
                <td class="text-center"> <?php echo read_date($customer['date_added']); ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_customer.php?id=<?php echo (int)$customer['customer_id'];?>" class="btn btn-info btn-xs"  title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_customer.php?id=<?php echo (int)$customer['customer_id'];?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
              </tr>
             <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <?php include_once('layouts/footer.php'); ?>