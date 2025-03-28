<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  $product = find_by_id('products',(int)$_GET['id']);
  if(!$product){
    $session->msg("d","Missing Product id.");
    redirect('product.php');
  }
?>
<?php
  // First check if this product has related records in sales_returns
  $product_id = (int)$product['id'];
  $sql_check = "SELECT COUNT(*) as count FROM sales_returns WHERE product_id = {$product_id}";
  $result_check = $db->query($sql_check);
  $count = $db->fetch_assoc($result_check);
  
  if($count['count'] > 0) {
    // Product has related records - can't delete
    $session->msg("d","Cannot delete product: It is referenced in sales returns records.");
    redirect('product.php');
  } else {
    // No related records, safe to delete
    $delete_id = delete_by_id('products', $product_id);
    if($delete_id){
      $session->msg("s","Product deleted successfully.");
      redirect('product.php');
    } else {
      $session->msg("d","Product deletion failed.");
      redirect('product.php');
    }
  }
?>
