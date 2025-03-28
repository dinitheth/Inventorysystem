<?php
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false); }
?>

<?php
 // Auto suggestion
    $html = '';
   if(isset($_POST['product_name']) && strlen($_POST['product_name']))
   {
     $products = find_product_by_title($_POST['product_name']);
     if($products){
        foreach ($products as $product):
           $html .= "<li class=\"list-group-item\">";
           $html .= $product['name'];
           $html .= "</li>";
         endforeach;
      } else {
        $html .= '<li onClick=\"fill(\''.addslashes().'\')\" class=\"list-group-item\">';
        $html .= 'Not found';
        $html .= "</li>";
      }
      echo json_encode($html);
   }
 ?>
 
<?php
 // Find all products by name
 if(isset($_POST['p_name']) && strlen($_POST['p_name'])) {
   $product_title = remove_junk($db->escape($_POST['p_name']));
   if($results = find_all_product_info_by_title($product_title)){
      foreach ($results as $result) {
          $html .= "<tr>";

          // Product Name
          $html .= "<td id=\"s_name\">".$result['name']."</td>";
          $html .= "<input type=\"hidden\" name=\"s_id[]\" value=\"{$result['id']}\">";

          // Price Input
          $html .= "<td><input type=\"text\" class=\"form-control\" name=\"price[]\" value=\"{$result['sale_price']}\"></td>";

          // Quantity Input
          $html .= "<td><input type=\"text\" class=\"form-control\" name=\"quantity[]\" value=\"1\"></td>";

          // Total Input
          $html .= "<td><input type=\"text\" class=\"form-control\" name=\"total[]\" value=\"{$result['sale_price']}\"></td>";

          // Action Button
          $html .= "<td><button type=\"button\" class=\"btn btn-danger remove-item\">Remove</button></td>";

          $html .= "</tr>";
      }
   } else {
      $html = '<tr><td colspan="6">Product name not registered in the database</td></tr>';
   }

   echo json_encode($html);
 }
?>

<?php
// Find Sale by ID for Returns
if(isset($_POST['find_sale']) && !empty($_POST['find_sale'])) {
  $html = '';
  $sale_id = remove_junk($db->escape($_POST['find_sale']));
  
  // Query to get sale details along with product information
  $sql = "SELECT s.id, s.qty, s.price, s.date, p.id AS product_id, p.name 
          FROM sales s 
          JOIN products p ON p.id = s.product_id 
          WHERE s.id = '{$sale_id}'";
          
  $result = $db->query($sql);
  
  if($db->num_rows($result) === 0) {
    $html = "<tr><td colspan='7'>No sale found with this ID.</td></tr>";
  } else {
    while($row = $db->fetch_assoc($result)) {
      $html .= "<tr>";
      $html .= "<td>{$row['id']}<input type='hidden' name='sale_id' value='{$row['id']}'></td>";
      $html .= "<td>{$row['name']}<input type='hidden' name='product_id' value='{$row['product_id']}'></td>";
      $html .= "<td>{$row['qty']}</td>";
      $html .= "<td><input type='number' name='return_qty' value='1' min='1' max='{$row['qty']}' data-original-qty='{$row['qty']}' class='form-control'></td>";
      $html .= "<td>{$row['price']}<input type='hidden' name='sale_price' value='{$row['price']}'></td>";
      $html .= "<td>
                  <select name='return_condition' class='form-control'>
                    <option value='good'>Good (Resellable)</option>
                    <option value='damaged'>Damaged</option>
                    <option value='defective'>Defective</option>
                    <option value='other'>Other</option>
                  </select>
                </td>";
      $html .= "<td><textarea name='reason' class='form-control' placeholder='Return reason'></textarea></td>";
      $html .= "</tr>";
    }
  }
  echo json_encode($html);
  exit;
}
?>