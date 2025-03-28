<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
?>
<?php
  // Custom function to find customer by customer_id
  function find_customer_by_id($id) {
    global $db;
    $id = (int)$id;
    $sql = "SELECT * FROM customers WHERE customer_id={$id} LIMIT 1";
    $result = $db->query($sql);
    return ($result && $db->num_rows($result) === 1) ? $db->fetch_assoc($result) : false;
  }

  // Custom function to delete customer by customer_id
  function delete_customer_by_id($id) {
    global $db;
    $id = (int)$id;
    $sql = "DELETE FROM customers WHERE customer_id={$id}";
    $result = $db->query($sql);
    return ($result && $db->affected_rows() === 1);
  }

  $d_customer = find_customer_by_id((int)$_GET['id']);
  if(!$d_customer){
    $session->msg("d","Missing customer id.");
    redirect('customers.php');
  }
?>
<?php
  $delete_id = delete_customer_by_id((int)$d_customer['customer_id']);
  if($delete_id){
      $session->msg("s","Customer deleted successfully.");
      redirect('customers_list.php');
  } else {
      $session->msg("d","Customer deletion failed.");
      redirect('customers_list.php');
  }
?>