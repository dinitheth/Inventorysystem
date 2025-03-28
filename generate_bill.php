<?php 
require('fpdf.php');
require_once('includes/load.php');
// Load database connection 
$page_title = 'Sales Receipt';
include_once('layouts/header.php'); 

// Get parameters from URL
$sale_id = '';
if (isset($_GET['id'])) {
    $sale_id = $_GET['id'];
} elseif (isset($_GET['sale_id'])) {
    $sale_id = $_GET['sale_id'];
}

// Get other parameters
$product_id = isset($_GET['s_id']) ? $_GET['s_id'] : '';
$quantity = isset($_GET['quantity']) ? $_GET['quantity'] : '';
$total = isset($_GET['total']) ? $_GET['total'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';

// Check if we have multiple items (comma-separated values)
$is_multi_item = (strpos($product_id, ',') !== false);

// Parse comma-separated values into arrays
$product_ids = explode(',', $product_id);
$quantities = explode(',', $quantity);
$totals = explode(',', $total);

// Calculate order total
$order_total = 0;
foreach($totals as $item_total) {
    $order_total += floatval($item_total);
}

// Get sale IDs for all products
$sale_ids = array();

// For each product ID, try to get the corresponding sale ID
for($i = 0; $i < count($product_ids); $i++) {
    if(empty($product_ids[$i])) continue;
    
    $sql = "SELECT id FROM sales WHERE product_id = '{$product_ids[$i]}' AND date = '{$date}'";
    
    // If we have quantity info, add it to the query
    if(isset($quantities[$i]) && !empty($quantities[$i])) {
        $sql .= " AND qty = '{$quantities[$i]}'";
    }
    
    // Order by most recent and limit to 1
    $sql .= " ORDER BY id DESC LIMIT 1";
    
    $result = $db->query($sql);
    if ($result && $db->num_rows($result) > 0) {
        $sale_record = $db->fetch_assoc($result);
        $sale_ids[$i] = $sale_record['id'];
    } else {
        $sale_ids[$i] = 'N/A';
    }
}

// Use first sale ID as the main sale ID if not already set
if(empty($sale_id) && !empty($sale_ids) && isset($sale_ids[0]) && $sale_ids[0] !== 'N/A') {
    $sale_id = $sale_ids[0];
}
?>

<div class="receipt-container">
    <h2>Sales Receipt</h2>
    <p><strong>Receipt#</strong> <?php echo $is_multi_item ? 'Multiple' : htmlspecialchars($sale_id); ?></p>
    <p><strong>Date:</strong> <?php echo htmlspecialchars($date); ?></p>
    
    <table class="items-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Loop through all items
            for($i = 0; $i < count($product_ids); $i++): 
                if(empty($product_ids[$i])) continue;
                
                // Get product name
                $product = find_by_id('products', $product_ids[$i]);
                $product_name = $product ? $product['name'] : 'Unknown Product';
                
                // Use the sale ID we looked up earlier
                $item_sale_id = isset($sale_ids[$i]) ? $sale_ids[$i] : 'N/A';
            ?>
            <tr>
                <td><?php echo htmlspecialchars($item_sale_id); ?></td>
                <td><?php echo htmlspecialchars($product_name); ?></td>
                <td><?php echo isset($quantities[$i]) ? htmlspecialchars($quantities[$i]) : ''; ?></td>
                <td><?php echo isset($totals[$i]) ? htmlspecialchars($totals[$i]) : ''; ?> LKR</td>
            </tr>
            <?php endfor; ?>
            
            <tr class="total-row">
                <td colspan="3" class="text-right"><strong>Total:</strong></td>
                <td><strong><?php echo number_format($order_total, 2); ?> LKR</strong></td>
            </tr>
        </tbody>
    </table>
    
    <!-- Link to generate the PDF -->
    <a href="generate_pdf.php?id=<?php echo $sale_id; ?>&s_id=<?php echo $product_id; ?>&quantity=<?php echo $quantity; ?>&total=<?php echo $total; ?>&date=<?php echo $date; ?>"
        class="btn btn-success" target="_blank">
       Generate PDF Receipt
    </a>
    
    <!-- Back to Sales button -->
    <a href="sales.php" class="btn btn-primary">
        Back to Sales
    </a>
</div>

<style>
  .receipt-container {
    width: 450px;
    margin: 20px auto;
    padding: 20px;
    border: 2px solid #333;
    background-color: #fff;
    text-align: center;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
  }
  
  .receipt-container h2 {
    font-size: 20px;
    color: #333;
    margin-bottom: 15px;
  }
  
  .receipt-container p {
    font-size: 16px;
    margin: 5px 0;
    color: #555;
    text-align: left;
  }
  
  .items-table {
    width: 100%;
    border-collapse: collapse;
    margin: 15px 0;
  }
  
  .items-table th, .items-table td {
    padding: 8px;
    border-bottom: 1px solid #ddd;
    text-align: left;
  }
  
  .items-table th {
    background-color: #f4f4f4;
  }
  
  .total-row {
    font-weight: bold;
    border-top: 2px solid #333;
  }
  
  .text-right {
    text-align: right;
  }
  
  .btn {
    display: inline-block;
    padding: 8px 16px;
    margin: 15px 5px;
    background-color: #28a745;
    color: white;
    text-decoration: none;
    border-radius: 4px;
  }
  
  .btn-primary {
    background-color: #007bff;
  }
  
  .btn:hover {
    background-color: #218838;
  }
  
  .btn-primary:hover {
    background-color: #0069d9;
  }
</style>

<?php include_once('layouts/footer.php'); ?>