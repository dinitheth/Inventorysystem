<?php
 $errors = array();

 /*--------------------------------------------------------------*/
 /* Function for Remove escapes special
 /* characters in a string for use in an SQL statement
 /*--------------------------------------------------------------*/
function real_escape($str){
  global $con;
  $escape = mysqli_real_escape_string($con,$str);
  return $escape;
}
/*--------------------------------------------------------------*/
/* Function for Remove html characters
/*--------------------------------------------------------------*/
function remove_junk($str){
  $str = nl2br($str);
  $str = htmlspecialchars(strip_tags($str, ENT_QUOTES));
  return $str;
}
/*--------------------------------------------------------------*/
/* Function for Uppercase first character
/*--------------------------------------------------------------*/
function first_character($str){
  $val = str_replace('-'," ",$str);
  $val = ucfirst($val);
  return $val;
}
/*--------------------------------------------------------------*/
/* Function for Checking input fields not empty
/*--------------------------------------------------------------*/
function validate_fields($var){
  global $errors;
  foreach ($var as $field) {
    $val = remove_junk($_POST[$field]);
    if(isset($val) && $val==''){
      $errors = $field ." can't be blank.";
      return $errors;
    }
  }
}
/*--------------------------------------------------------------*/
/* Function for Display Session Message
   Ex echo displayt_msg($message);
/*--------------------------------------------------------------*/
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
/*--------------------------------------------------------------*/
/* Function for redirect
/*--------------------------------------------------------------*/
function redirect($url, $permanent = false)
{
    if (headers_sent() === false)
    {
      header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    }

    exit();
}

/*--------------------------------------------------------------*/
/* Function for find out total saleing price, buying price and profit
/*--------------------------------------------------------------*/
function total_price($totals){
   $sum = 0;
   $sub = 0;
   foreach($totals as $total ){
     $sum += $total['total_saleing_price'];
     $sub += $total['total_buying_price'];
     $profit = $sum - $sub;
   }
   return array($sum,$profit);

}
/*--------------------------------------------------------------*/
/* Function for Readable date time
/*--------------------------------------------------------------*/
function read_date($str){
     if($str)
      return date('F j, Y, g:i:s a', strtotime($str));
     else
      return null;
  }
  
/*--------------------------------------------------------------*/
/* Function for  Readable Make date time
/*--------------------------------------------------------------*/
function make_date(){
  return strftime("%Y-%m-%d %H:%M:%S", time());
}
/*--------------------------------------------------------------*/
/* Function for  Readable date time
/*--------------------------------------------------------------*/
function count_id(){
  static $count = 1;
  return $count++;
}
/*--------------------------------------------------------------*/
/* Function for Creting random string
/*--------------------------------------------------------------*/
function randString($length = 5)
{
  $str='';
  $cha = "0123456789abcdefghijklmnopqrstuvwxyz";

  for($x=0; $x<$length; $x++)
   $str .= $cha[mt_rand(0,strlen($cha))];
  return $str;
}

/*--------------------------------------------------------------*/
/* Function for Counting low stock items
/*--------------------------------------------------------------*/
function count_low_stock() {
  global $db;
  $sql = "SELECT COUNT(*) as count FROM products WHERE quantity <= minimum_quantity";
  $result = $db->query($sql);
  if($result) {
    $row = $db->fetch_assoc($result);
    return $row['count'];
  }
  return 0;
}

/*--------------------------------------------------------------*/
/* Function for Getting low stock items
/*--------------------------------------------------------------*/
function get_low_stock_items() {
  global $db;
  $sql = "SELECT name, quantity, minimum_quantity FROM products WHERE quantity <= minimum_quantity ORDER BY quantity ASC";
  $result = $db->query($sql);
  $low_stock = array();
  if($result) {
    while($row = $db->fetch_assoc($result)) {
      $low_stock[] = $row;
    }
  }
  return $low_stock;
}


/*--------------------------------------------------------------*/
/* Function for find all sales returns with product and user information
/*--------------------------------------------------------------*/
function find_all_returns() {
  global $db;
  $sql = "SELECT r.id, r.sale_id, r.product_id, r.quantity, r.price, r.return_date, r.reason, r.return_condition, r.returned_by, 
          p.name as product_name, u.name as username 
          FROM sales_returns r 
          JOIN products p ON p.id = r.product_id 
          JOIN users u ON u.id = r.returned_by 
          ORDER BY r.return_date DESC";
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Function for find sales return by ID
/*--------------------------------------------------------------*/
function find_return_by_id($id) {
  global $db;
  $id = (int)$id;
  $sql = "SELECT r.*, p.name as product_name, u.name as username 
          FROM sales_returns r 
          JOIN products p ON p.id = r.product_id 
          JOIN users u ON u.id = r.returned_by 
          WHERE r.id = '{$id}' LIMIT 1";
  $result = find_by_sql($sql);
  return $result ? $result[0] : null;
}

/*--------------------------------------------------------------*/
/* Function for find all returns for a specific sale
/*--------------------------------------------------------------*/
function find_returns_by_sale($sale_id) {
  global $db;
  $sale_id = (int)$sale_id;
  $sql = "SELECT r.*, p.name as product_name 
          FROM sales_returns r 
          JOIN products p ON p.id = r.product_id 
          WHERE r.sale_id = '{$sale_id}'";
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Function for find all returns for a specific product
/*--------------------------------------------------------------*/
function find_returns_by_product($product_id) {
  global $db;
  $product_id = (int)$product_id;
  $sql = "SELECT r.*, p.name as product_name 
          FROM sales_returns r 
          JOIN products p ON p.id = r.product_id 
          WHERE r.product_id = '{$product_id}'";
  return find_by_sql($sql);
}

?>
