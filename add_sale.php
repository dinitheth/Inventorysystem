<?php
  $page_title = 'Add Sale';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
?>

<?php
if (isset($_POST['add_sale'])) {
    // Modified validation approach for array inputs
    $valid = true;
    
    // Check if required arrays exist
    if(!isset($_POST['s_id']) || !isset($_POST['quantity']) || !isset($_POST['price']) || !isset($_POST['total'])) {
        $session->msg("d", "Missing required fields");
        redirect('add_sale.php', false);
        exit;
    }
    
    // Check if arrays are empty
    if(empty($_POST['s_id']) || empty($_POST['quantity']) || empty($_POST['price']) || empty($_POST['total'])) {
        $session->msg("d", "Please add at least one product");
        redirect('add_sale.php', false);
        exit;
    }

    // If validation passes, proceed with processing
    // Escape the inputs for security
    $product_ids   = $_POST['s_id'];       // Array of product IDs
    $quantities    = $_POST['quantity'];   // Array of quantities
    $total_prices  = $_POST['total'];      // Array of total prices

    // Get current date in 'Y-m-d' format
    $s_date = date('Y-m-d');

    // Prepare the SQL query for batch insert
    $sql = "INSERT INTO sales (product_id, qty, price, date) VALUES ";

    // Loop through all products and build the values part of the SQL query
    $values = [];
    for ($i = 0; $i < count($product_ids); $i++) {
        // Escape each value individually to prevent SQL injection
        $product_id = $db->escape($product_ids[$i]);
        $quantity = $db->escape($quantities[$i]);
        $total_price = $db->escape($total_prices[$i]);
        
        $values[] = "('$product_id', '$quantity', '$total_price', '$s_date')";
    }

    // Append values to the SQL query
    $sql .= implode(", ", $values);

    // Execute the batch insert query
    if ($db->query($sql)) {
        // Update product quantities for all the sales
        for ($i = 0; $i < count($product_ids); $i++) {
            update_product_qty($quantities[$i], $product_ids[$i]);
        }
        
        // Success message and redirection
        $session->msg('s', "Sales added.");
        redirect("generate_bill.php?s_id=" . implode(',', $product_ids) . "&quantity=" . implode(',', $quantities) . "&total=" . implode(',', $total_prices) . "&date=$s_date", false);
    } else {
        // Error message if the query failed
        $session->msg('d', 'Sorry, failed to add sales!');
        redirect('add_sale.php', false);
    }
}
?>

<?php include_once('layouts/header.php'); ?>
<!-- Rest of your HTML code remains the same -->
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
    <form method="post" action="ajax.php" autocomplete="off" id="sug-form">
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary">Find It</button>
            </span>
            <input type="text" id="sug_input" class="form-control" name="title"  placeholder="Search for product name">
         </div>
         <div id="result" class="list-group"></div>
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
          <span>Add Sales</span>
       </strong>
      </div>
      <div class="panel-body">
      <form method="post" action="add_sale.php" id="sale-form">
      <table class="table table-bordered">
    <thead>
      <th> Item </th>
      <th> Price </th>
      <th> Qty </th>
      <th> Total </th>
      <th> Action</th>
    </thead>
    <tbody id="product_info">
      <!-- Product rows will be added here dynamically -->
    </tbody>
    <!-- Total Row -->
    <tfoot>
        <tr>
            <td colspan="3" style="text-align: right;"><b>Total Value:</b></td>
            <td id="total-value"><b>0.00</b></td>
            <td></td>
        </tr>
    </tfoot>
</table>
  <button type="submit" name="add_sale" class="btn btn-primary">Add Sale</button>
</form>

</div>
</div>
</div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    console.log("Document ready, initializing sales form...");

    // Function to update the total at the bottom
    function updateTotal() {
        let totalAmount = 0;

        // Loop through each row and sum the total values
        $('input[name="total[]"]').each(function() {
            totalAmount += parseFloat($(this).val()) || 0; // Ensure it handles NaN or undefined
        });

        // Update the total value in the last row
        $('#total-value').text(totalAmount.toFixed(2)); // Format to 2 decimal places
    }

    // Trigger when search button is clicked (on the search form)
    $('#sug-form button[type="submit"]').on('click', function(e) {
        e.preventDefault();
        let product_name = $('#sug_input').val();
        
        // AJAX request to get product details
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: { p_name: product_name },
            success: function(response) {
                try {
                    let data = JSON.parse(response);
                    $('#product_info').append(data); // Append the rows to the table

                    // Clear the search input field after the search
                    $('#sug_input').val(''); // Clears the search input field

                    // Update totals after a new row is added
                    updateTotal();
                } catch (e) {
                    console.error("Error parsing JSON response:", e);
                    alert("Error adding product: " + e.message);
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX error: ' + error); // Debugging the AJAX error
            }
        });
    });

    // Calculate total when quantity or price is changed
    $(document).on('input', 'input[name="quantity[]"], input[name="price[]"]', function() {
        let row = $(this).closest('tr');
        let price = parseFloat(row.find('input[name="price[]"]').val());
        let quantity = parseFloat(row.find('input[name="quantity[]"]').val());

        // Check if the price or quantity is not valid or is 0
        if (isNaN(price) || isNaN(quantity) || quantity <= 0 || price <= 0) {
            row.find('input[name="total[]"]').val('0.00'); // Set total to 0 if invalid or 0
        } else {
            let total = price * quantity;
            row.find('input[name="total[]"]').val(total.toFixed(2)); // Update the total
        }

        // Update the overall total after every change
        updateTotal();
    });

    // Add the submit handler back for the sales form
    // This is what was missing in your updated code
    $('#sale-form').on('submit', function(e) {
        // Verify we have products before submitting
        if ($('#product_info tr').length === 0) {
            e.preventDefault();
            alert("Please add at least one product before submitting.");
            return false;
        }
        
        // Let the form submit normally - this is important!
        // Do not prevent default here, let the form submit naturally
        // The PHP will handle the rest
        
        return true; // Allow form submission
    });

    // Event to remove item (remove the row from the table when clicked)
    $(document).on('click', '.remove-item', function() {
        $(this).closest('tr').remove();

        // Update the overall total after row removal
        updateTotal();
    });

    // Initially update the total if there are pre-existing rows
    updateTotal();
});
</script>

<?php include_once('layouts/footer.php'); ?>


