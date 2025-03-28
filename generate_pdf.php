<?php
require('fpdf.php');
require_once('includes/load.php');

// Check if sale details are passed in the URL
if (isset($_GET['s_id'])) {
    // Get parameters
    $sale_id = isset($_GET['id']) ? $_GET['id'] : '';
    $product_id = $_GET['s_id'];
    $quantity = $_GET['quantity'];
    $total = $_GET['total'];
    $date = $_GET['date'];

    // Check if we have multiple items (comma-separated values)
    $is_multi_item = (strpos($quantity, ',') !== false);

    // Parse comma-separated values into arrays
    $product_ids = explode(',', $product_id);
    $quantities = explode(',', $quantity);
    $totals = explode(',', $total);

    // Calculate order total
    $order_total = 0;
    foreach($totals as $item_total) {
        $order_total += floatval($item_total);
    }

    // Create PDF document
    $pdf = new FPDF();
    $pdf->AddPage();

    // Set Font for Store Name (Top of the PDF)
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->Cell(190, 10, 'K A Electricals', 0, 1, 'C'); // Store name at the top
    
    // Company Address
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(190, 5, '428/C, Colombo Road, Kohuwala', 0, 1, 'C');
    $pdf->Cell(190, 5, 'Tel: +94 714 027 788 | Email: kaelectrials@gmail.com', 0, 1, 'C');
    $pdf->Line(10, 30, 200, 30); // Horizontal line under header
    $pdf->Ln(5);

    // Welcome Message
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, 'WELCOME TO K A ELECTRICALS!', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(190, 5, 'Thank you for shopping with us. We appreciate your business and hope you are satisfied with your purchase.', 0, 'C');
    $pdf->Ln(5);

    // Receipt Title
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(190, 10, 'SALES RECEIPT', 0, 1, 'C');
    $pdf->Ln(2);

    // Sale ID and Date
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'Receipt# ' . $sale_id);
    $pdf->Cell(150, 10, 'Date: ' . $date, 0, 1, 'R');
    $pdf->Ln(2);

    // Table Header
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(200, 200, 200);
    $pdf->Cell(20, 10, 'ID', 1, 0, 'C', true);
    $pdf->Cell(90, 10, 'Item', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Quantity', 1, 0, 'C', true);
    $pdf->Cell(50, 10, 'Price (LKR)', 1, 1, 'C', true);

    // Table Data
    $pdf->SetFont('Arial', '', 12);
    
    // Loop through all items
    for($i = 0; $i < count($product_ids); $i++) {
        // Get product name
        $product = find_by_id('products', $product_ids[$i]);
        $product_name = $product ? $product['name'] : 'Unknown Product';
        
        // For multi-item receipts, we need to look up individual sale IDs
        $item_sale_id = $sale_id;
        if($is_multi_item) {
            // Try to look up the specific sale ID for this product
            $sql = "SELECT id FROM sales WHERE product_id = '{$product_ids[$i]}' AND date = '{$date}' ORDER BY id DESC LIMIT 1";
            $result = $db->query($sql);
            if ($db->num_rows($result) > 0) {
                $sale_record = $db->fetch_assoc($result);
                $item_sale_id = $sale_record['id'];
            }
        }
        
        $pdf->Cell(20, 10, $item_sale_id, 1, 0, 'C');
        $pdf->Cell(90, 10, $product_name, 1, 0, 'L');
        $pdf->Cell(30, 10, $quantities[$i], 1, 0, 'C');
        $pdf->Cell(50, 10, $totals[$i], 1, 1, 'R');
    }
    
    // Total Row
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(140, 10, 'Total:', 1, 0, 'R');
    $pdf->Cell(50, 10, number_format($order_total, 2), 1, 1, 'R');
    
    // Return Policy
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, 'RETURN POLICY:', 0, 1);
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(190, 5, 'Products may be returned within 7 days of purchase with the original receipt. Electrical items must be unopened and in their original packaging. Defective products can be exchanged for the same item. Special orders and sale items are final sale. For more information, please contact our customer service.', 0, 'L');
    
    // Thank You Message
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(190, 10, 'Thank you for your purchase! We value your business.', 0, 1, 'C');
    
    // Space before signature
    $pdf->Ln(10);
    
    // Add signature lines
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(190, 10, 'Customer Name & Sign: ____________________ & ____________________', 0, 1);
    $pdf->Ln(5);
    $pdf->Cell(190, 10, 'Sign By : ____________________ (Kavindu, K.A Electricals)', 0, 1);

    // Footer with contact information
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'I', 8);
    $pdf->Cell(190, 5, 'For questions or concerns about your purchase, please contact us:', 0, 1, 'C');
    
    // Output the PDF (download)
    $pdf->Output();
}
?>