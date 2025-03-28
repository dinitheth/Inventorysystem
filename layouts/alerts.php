<?php
// Display alert messages
function display_msg($msg =''){
   $output = array();
   if(!empty($msg)) {
      foreach ($msg as $key => $value) {
         $output  = "<div class=\"alert alert-{$key}\">";
         $output .= "<a href=\"#\" class=\"close\" data-dismiss=\"alert\">&times;</a>";
         $output .= remove_junk(first_character($value));
         $output .= "</div>";
      }
      return $output;
   } else {
     return "" ;
   }
}

// Function to count low stock items
function count_low_stock() {
  if(function_exists('get_low_stock_items')) {
    $low_stock_items = get_low_stock_items();
    return count($low_stock_items);
  }
  return 0;
}

// Function to display low stock alerts globally
function display_global_low_stock_alerts() {
  if(function_exists('get_low_stock_items')) {
    $low_stock_items = get_low_stock_items();
    if(!empty($low_stock_items)) {
      echo '<div class="alert alert-warning" style="animation: blink 2s infinite;">';
      echo '<strong>⚠️ Low Stock Alert!</strong> ';
      echo '<a href="notifications.php" class="alert-link">View Details</a>';
      echo '<ul class="list-unstyled mt-2" style="max-height: 100px; overflow-y: auto;">';
      
      foreach($low_stock_items as $item) {
        echo '<li class="stock-alert-item">';
        echo '<strong>' . remove_junk($item['name']) . '</strong> - ';
        echo 'Current Stock: <span class="text-danger">' . $item['quantity'] . '</span>';
        echo '</li>';
      }
      
      echo '</ul>';
      echo '</div>';
    }
  }
}

// Function to display low stock popup notification
function display_low_stock_popup() {
  $low_stock_count = count_low_stock();
  
  if($low_stock_count > 0) {
    echo '<script>
      $(document).ready(function() {
        toastr.options = {
          "closeButton": true,
          "progressBar": true,
          "positionClass": "toast-top-right",
          "timeOut": "7000"
        };
        toastr.warning("' . $low_stock_count . ' items are low in stock! <a href=\'notifications.php\'>View Details</a>", "Low Stock Alert");
      });
    </script>';
  }
}
?>

<style>
@keyframes blink {
    0% { opacity: 1; }
    50% { opacity: 0.8; }
    100% { opacity: 1; }
}

.stock-alert-item {
    padding: 4px;
    margin-bottom: 3px;
    border-bottom: 1px solid #eee;
}

.alert-warning {
    background-color: #fff3cd;
    border: 1px solid #ffeeba;
    color: #856404;
}
</style>
