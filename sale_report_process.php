<?php
$page_title = 'Sales Report';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php
  if(isset($_POST['submit'])){
    $req_dates = array('start-date','end-date');
    validate_fields($req_dates);

    if(empty($errors)):
      $start_date   = remove_junk($db->escape($_POST['start-date']));
      $end_date     = remove_junk($db->escape($_POST['end-date']));
      $results      = find_sale_by_dates($start_date,$end_date);
    else:
      $session->msg("d", $errors);
      redirect('sales_report.php', false);
    endif;

  } else {
    $session->msg("d", "Select dates");
    redirect('sales_report.php', false);
  }

  // Calculate various business metrics
  function calculate_business_metrics($results) {
      if(empty($results)) {
          return [
              'total_revenue' => 0,
              'total_cost' => 0, 
              'gross_profit' => 0,
              'profit_margin' => 0,
              'total_items_sold' => 0,
              'unique_products' => 0,
              'avg_selling_price' => 0
          ];
      }
      
      $total_revenue = 0;
      $total_cost = 0;
      $total_items = 0;
      $products = [];
      
      foreach($results as $sale) {
          // Ensure these values are numerical
          $revenue = floatval($sale['total_saleing_price']);
          $cost = floatval($sale['buy_price']) * intval($sale['total_sales']);
          $qty = intval($sale['total_sales']);
          
          $total_revenue += $revenue;
          $total_cost += $cost;
          $total_items += $qty;
          
          // Track unique products
          if(!in_array($sale['name'], $products)) {
              $products[] = $sale['name'];
          }
      }
      
      $gross_profit = $total_revenue - $total_cost;
      $profit_margin = ($total_revenue > 0) ? ($gross_profit / $total_revenue) * 100 : 0;
      $avg_selling_price = ($total_items > 0) ? $total_revenue / $total_items : 0;
      
      return [
          'total_revenue' => $total_revenue,
          'total_cost' => $total_cost,
          'gross_profit' => $gross_profit,
          'profit_margin' => $profit_margin,
          'total_items_sold' => $total_items,
          'unique_products' => count($products),
          'avg_selling_price' => $avg_selling_price
      ];
  }
  
  // Get metrics if results exist
  $metrics = calculate_business_metrics($results);
?>
<!doctype html>
<html lang="en-US">
 <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <title>Sales Report (<?php echo $start_date; ?> - <?php echo $end_date; ?>)</title>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
   <style>
   @media print {
     html,body{
        font-size: 9.5pt;
        margin: 0;
        padding: 0;
     }.page-break {
       page-break-before:always;
       width: auto;
       margin: auto;
      }
    }
    .page-break{
      width: 980px;
      margin: 0 auto;
    }
     .sale-head{
       margin: 40px 0;
       text-align: center;
     }.sale-head h1,.sale-head strong{
       padding: 10px 20px;
       display: block;
     }.sale-head h1{
       margin: 0;
       border-bottom: 1px solid #212121;
     }.table>thead:first-child>tr:first-child>th{
       border-top: 1px solid #000;
      }
      table thead tr th {
       text-align: center;
       border: 1px solid #ededed;
     }table tbody tr td{
       vertical-align: middle;
     }.sale-head,table.table thead tr th,table tbody tr td,table tfoot tr td{
       border: 1px solid #212121;
       white-space: nowrap;
     }.sale-head h1,table thead tr th,table tfoot tr td{
       background-color: #f8f8f8;
     }tfoot{
       color:#000;
       text-transform: uppercase;
       font-weight: 500;
     }
     
     /* New styles for the metrics dashboard */
     .metrics-dashboard {
         margin: 20px 0;
         display: flex;
         flex-wrap: wrap;
         border: 1px solid #ddd;
         border-radius: 4px;
         overflow: hidden;
     }
     .metric-box {
         flex: 1;
         min-width: 150px;
         padding: 15px 10px;
         text-align: center;
         border-right: 1px solid #ddd;
         background-color: #f9f9f9;
     }
     .metric-box:last-child {
         border-right: none;
     }
     .metric-value {
         font-size: 24px;
         font-weight: bold;
         color: #333;
         margin: 5px 0;
     }
     .metric-label {
         font-size: 12px;
         text-transform: uppercase;
         color: #777;
     }
     .profit-positive {
         color: #28a745;
     }
     .profit-negative {
         color: #dc3545;
     }
     .percent-badge {
         font-size: 14px;
         padding: 2px 8px;
         border-radius: 10px;
         margin-left: 5px;
     }
     .report-period {
         font-size: 16px;
         color: #555;
         margin-bottom: 20px;
     }
     
     /* Make certain columns right-aligned for numbers */
     .text-right {
         text-align: right;
     }
     
     /* Alternate row colors for better readability */
     tbody tr:nth-child(odd) {
         background-color: #f2f2f2;
     }
     
     /* Small icon colors */
     .fa-arrow-up {
         color: #28a745;
     }
     .fa-arrow-down {
         color: #dc3545;
     }
   </style>
</head>
<body>
  <?php if($results): ?>
    <div class="page-break">
       <div class="sale-head">
           <h1>KA Electricals - Sales Report</h1>
           <div class="report-period">
               <strong>Period: <?php echo date("d M Y", strtotime($start_date)); ?> to <?php echo date("d M Y", strtotime($end_date)); ?></strong>
           </div>
       </div>
       
       <!-- Business Metrics Dashboard -->
       <div class="metrics-dashboard">
           <div class="metric-box">
               <div class="metric-label">Total Revenue</div>
               <div class="metric-value">Rs. <?php echo number_format($metrics['total_revenue'], 2); ?></div>
           </div>
           <div class="metric-box">
               <div class="metric-label">Total Cost</div>
               <div class="metric-value">Rs. <?php echo number_format($metrics['total_cost'], 2); ?></div>
           </div>
           <div class="metric-box">
               <div class="metric-label">Gross Profit</div>
               <div class="metric-value <?php echo ($metrics['gross_profit'] >= 0) ? 'profit-positive' : 'profit-negative'; ?>">
                   Rs. <?php echo number_format($metrics['gross_profit'], 2); ?>
                   <?php if($metrics['gross_profit'] > 0): ?>
                   <i class="fa fa-arrow-up"></i>
                   <?php elseif($metrics['gross_profit'] < 0): ?>
                   <i class="fa fa-arrow-down"></i>
                   <?php endif; ?>
               </div>
           </div>
           <div class="metric-box">
               <div class="metric-label">Items Sold</div>
               <div class="metric-value"><?php echo $metrics['total_items_sold']; ?></div>
           </div>
           <div class="metric-box">
               <div class="metric-label">Avg. Price</div>
               <div class="metric-value">Rs. <?php echo number_format($metrics['avg_selling_price'], 2); ?></div>
           </div>
       </div>
       
      <table class="table table-bordered">
        <thead>
          <tr>
              <th>Date</th>
              <th>Product Name</th>
              <th class="text-right">Unit Cost</th>
              <th class="text-right">Unit Price</th>
              <th class="text-right">Quantity</th>
              <th class="text-right">Total Cost</th>
              <th class="text-right">Total Revenue</th>
              <th class="text-right">Profit</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($results as $result): 
            // Calculate additional metrics for each row
            $total_cost = floatval($result['buy_price']) * intval($result['total_sales']);
            $total_revenue = floatval($result['total_saleing_price']);
            $profit = $total_revenue - $total_cost;
          ?>
           <tr>
              <td><?php echo date("d/m/Y", strtotime(remove_junk($result['date']))); ?></td>
              <td>
                <?php echo remove_junk(ucfirst($result['name'])); ?>
              </td>
              <td class="text-right">Rs. <?php echo number_format(remove_junk($result['buy_price']), 2); ?></td>
              <td class="text-right">Rs. <?php echo number_format(remove_junk($result['sale_price']), 2); ?></td>
              <td class="text-right"><?php echo remove_junk($result['total_sales']); ?></td>
              <td class="text-right">Rs. <?php echo number_format($total_cost, 2); ?></td>
              <td class="text-right">Rs. <?php echo number_format($total_revenue, 2); ?></td>
              <td class="text-right <?php echo ($profit >= 0) ? 'profit-positive' : 'profit-negative'; ?>">
                Rs. <?php echo number_format($profit, 2); ?>
              </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
         <tr>
           <td colspan="4" class="text-right"><strong>TOTALS</strong></td>
           <td class="text-right"><strong><?php echo $metrics['total_items_sold']; ?></strong></td>
           <td class="text-right"><strong>Rs. <?php echo number_format($metrics['total_cost'], 2); ?></strong></td>
           <td class="text-right"><strong>Rs. <?php echo number_format($metrics['total_revenue'], 2); ?></strong></td>
           <td class="text-right <?php echo ($metrics['gross_profit'] >= 0) ? 'profit-positive' : 'profit-negative'; ?>">
             <strong>Rs. <?php echo number_format($metrics['gross_profit'], 2); ?></strong>
           </td>
         </tr>
        </tfoot>
      </table>
      
      <!-- Add a print button -->
      <div style="text-align: center; margin: 20px 0;">
        <button onclick="window.print();" class="btn btn-primary">
          <i class="fa fa-print"></i> Print Report
        </button>
        <a href="sales_report.php" class="btn btn-info">
          <i class="fa fa-arrow-left"></i> Back
        </a>
      </div>
    </div>
  <?php
    else:
        $session->msg("d", "No sales found for the selected date range.");
        redirect('sales_report.php', false);
     endif;
  ?>
</body>
</html>
<?php if(isset($db)) { $db->db_disconnect(); } ?>