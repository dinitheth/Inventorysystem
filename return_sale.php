<?php
  $page_title = 'Return Sale';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
?>

<?php
// Function to update product quantity when returning items
function add_product_qty($qty, $product_id) {
  global $db;
  $qty = (int)$qty;
  $id = (int)$product_id;
  $sql = "UPDATE products SET quantity = quantity + '{$qty}' WHERE id = '{$id}'";
  $result = $db->query($sql);
  return($result);
}

// Process the sale return
if(isset($_POST['return_sale'])) {
  $req_fields = array('sale_id', 'product_id', 'return_qty', 'sale_price', 'return_condition', 'reason');
  validate_fields($req_fields);

  if(empty($errors)) {
    $s_id      = $db->escape((int)$_POST['sale_id']);
    $p_id      = $db->escape((int)$_POST['product_id']);
    $s_qty     = $db->escape((int)$_POST['return_qty']);
    $s_price   = $db->escape($_POST['sale_price']);
    $condition = $db->escape($_POST['return_condition']);
    $reason    = $db->escape($_POST['reason']);
    $return_date = make_date();
    $user_id   = (int)$_SESSION['user_id'];

    // First, check if the sale exists and the return quantity is valid
    $sale_query = $db->query("SELECT * FROM sales WHERE id = '{$s_id}'");
    if($db->num_rows($sale_query) === 0) {
      $session->msg('d', 'Sale record not found.');
      redirect('sales.php', false);
    }

    $sale = $db->fetch_assoc($sale_query);
    if($s_qty > $sale['qty']) {
      $session->msg('d', 'Return quantity cannot exceed the original sale quantity.');
      redirect('sales.php', false);
    }

    // Start a transaction to ensure data consistency
    $db->query("START TRANSACTION");

    // Insert the return record
    $sql = "INSERT INTO sales_returns (sale_id, product_id, quantity, price, return_date, reason, return_condition, returned_by) 
            VALUES ('{$s_id}', '{$p_id}', '{$s_qty}', '{$s_price}', '{$return_date}', '{$reason}', '{$condition}', '{$user_id}')";
    
    $result = $db->query($sql);

    if($result) {
      // Update the product quantity (add the returned items back to inventory)
      add_product_qty($s_qty, $p_id);

      // Update the original sale record (deduct the returned quantity)
      if($s_qty == $sale['qty']) {
        // If all items are returned, delete the sale record
        $db->query("DELETE FROM sales WHERE id = '{$s_id}'");
      } else {
        // Otherwise, update the sale quantity and total price
        $new_qty = $sale['qty'] - $s_qty;
        $new_total = $new_qty * $sale['price'];
        $db->query("UPDATE sales SET qty = '{$new_qty}' WHERE id = '{$s_id}'");
      }
      
      // Commit the transaction
      $db->query("COMMIT");
      $session->msg('s', "Sale return processed successfully.");
      redirect('sales.php', false);
    } else {
      // Rollback on error
      $db->query("ROLLBACK");
      $session->msg('d', 'Sorry, return failed.');
      redirect('return_sale.php', false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('return_sale.php', false);
  }
}
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
    <form method="post" action="ajax.php" autocomplete="off" id="find-sale-form">
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary">Find Sale</button>
            </span>
            <input type="text" id="sale_id_input" class="form-control" name="sale_id" placeholder="Enter Sale ID in Receipt">
         </div>
         <div id="sale_result" class="list-group"></div>
        </div>
    </form>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Return Sale</span>
       </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="return_sale.php" id="return-sale-form">
          <table class="table table-bordered">
            <thead>
              <th>Sale ID</th>
              <th>Product</th>
              <th>Original Qty</th>
              <th>Return Qty</th>
              <th>Price</th>
              <th>Return Condition</th>
              <th>Reason</th>
            </thead>
            <tbody id="sale_info">
              <!-- Sale info will be loaded here -->
            </tbody>
          </table>
          <button type="submit" name="return_sale" class="btn btn-danger">Process Return</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  // Find sale button click
  $('#find-sale-form button[type="submit"]').on('click', function(e) {
    e.preventDefault();
    let sale_id = $('#sale_id_input').val();
    
    if (!sale_id) {
      alert('Please enter a Sale ID');
      return false;
    }
    
    // AJAX request to get sale details
    $.ajax({
      type: "POST",
      url: "ajax.php",
      data: { find_sale: sale_id },
      success: function(response) {
        try {
          let data = JSON.parse(response);
          $('#sale_info').html(data);
          $('#sale_id_input').val('');
        } catch (e) {
          console.error("Error parsing JSON response:", e);
          alert("Error finding sale: " + e.message);
        }
      },
      error: function(xhr, status, error) {
        console.log('AJAX error: ' + error);
      }
    });
  });
  
  // Validate return quantity
  $(document).on('input', 'input[name="return_qty"]', function() {
    let return_qty = parseInt($(this).val()) || 0;
    let original_qty = parseInt($(this).attr('data-original-qty')) || 0;
    
    if (return_qty <= 0) {
      alert('Return quantity must be greater than 0');
      $(this).val(1);
    } else if (return_qty > original_qty) {
      alert('Return quantity cannot exceed original quantity');
      $(this).val(original_qty);
    }
  });
  
  // Form submission validation
  $('#return-sale-form').on('submit', function(e) {
    if ($('#sale_info').children().length === 0) {
      e.preventDefault();
      alert("Please find a sale to process a return.");
      return false;
    }
    
    return true;
  });
});
</script>
<?php include_once('layouts/footer.php'); ?>