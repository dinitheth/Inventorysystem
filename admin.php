<link rel="stylesheet" href="libs/css/bootstrap.min.css">
<link rel="stylesheet" href="libs/css/custom.css">

<style>
.dashboard-card {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 25px;
    border-radius: 16px;
    color: #ffffff;
    text-align: center;
    text-decoration: none;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    text-decoration: none;
}

.bg-green {
    background: linear-gradient(135deg, #6fcf97, #27ae60);
}

.card-icon {
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 5px;
}

.card-content h2 {
    font-size: 23px;
    margin: 0;
    color: #ffffff;
}

.card-content p {
    font-size: 15px;
    margin: 4px 0 0;
    color: #e0f2e9;
}
</style>


<?php
  $page_title = 'Admin Home Page';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>
<?php
 $c_categorie     = count_by_id('categories');
 $c_product       = count_by_id('products');
 $c_sale          = count_by_id('sales');
 $c_user          = count_by_id('users');
 
 // Get total sales amount (sum of all sales prices)
 $sql = "SELECT SUM(price) as total_sales FROM sales";
 $result = $db->query($sql);
 $total_sales_amount = $db->fetch_assoc($result);
 $total_sales_amount = $total_sales_amount['total_sales'];
 
 $products_sold   = find_higest_saleing_product('10');
 $recent_products = find_recent_product_added('5');
 $recent_sales    = find_recent_sale_added('5')
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-6">
     <?php echo display_msg($msg); ?>
   </div>
</div>

<!-- New Card-Based Dashboard Section -->
<div class="row dashboard-cards">
    <!-- Users Card -->
    <div class="col-lg-3 col-sm-6 col-12 d-flex">
        <a href="users.php" class="dashboard-card bg-secondary1">
            <div class="card-icon">
                <i class="glyphicon glyphicon-user"></i>
            </div>
            <div class="card-content">
                <h2><?php echo $c_user['total']; ?></h2>
                <p>Users</p>
            </div>
        </a>
    </div>

    <!-- Categories Card -->
    <div class="col-lg-3 col-sm-6 col-12 d-flex">
        <a href="categorie.php" class="dashboard-card bg-red">
            <div class="card-icon">
                <i class="glyphicon glyphicon-th-large"></i>
            </div>
            <div class="card-content">
                <h2><?php echo $c_categorie['total']; ?></h2>
                <p>Categories</p>
            </div>
        </a>
    </div>

    <!-- Products Card -->
    <div class="col-lg-3 col-sm-6 col-12 d-flex">
        <a href="product.php" class="dashboard-card bg-blue2">
            <div class="card-icon">
                <i class="glyphicon glyphicon-shopping-cart"></i>
            </div>
            <div class="card-content">
                <h2><?php echo $c_product['total']; ?></h2>
                <p>Products</p>
            </div>
        </a>
    </div>

    <!-- Sales Card - Updated to show total sales amount -->
    <div class="col-lg-3 col-sm-6 col-12 d-flex">
        <a href="sales.php" class="dashboard-card bg-green">
            <div class="card-icon" style="font-size: 22px; font-weight: bold; margin-bottom: -0.3px;">
                R.s
            </div>
            <div class="card-content">
                <h2 style="margin-top: 0;"><?php echo number_format($total_sales_amount, 2); ?></h2>
                <p>Total Sales (<?php echo $c_sale['total']; ?> orders)</p>
            </div>
        </a>
    </div>
</div>
<br>
<!-- Rest of the original content remains the same -->
<div class="row">
   <div class="col-md-4">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span>Highest Selling Products</span>
         </strong>
       </div>
       <div class="panel-body">
         <table class="table table-striped table-bordered table-condensed">
          <thead>
           <tr>
             <th>Title</th>
             <th>Total Sold</th>
             <th>Total Quantity</th>
           <tr>
          </thead>
          <tbody>
            <?php foreach ($products_sold as  $product_sold): ?>
              <tr>
                <td><?php echo remove_junk(first_character($product_sold['name'])); ?></td>
                <td><?php echo (int)$product_sold['totalSold']; ?></td>
                <td><?php echo (int)$product_sold['totalQty']; ?></td>
              </tr>
            <?php endforeach; ?>
          <tbody>
         </table>
       </div>
     </div>
   </div>
   <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>LATEST SALES</span>
          </strong>
        </div>
        <div class="panel-body">
          <table class="table table-striped table-bordered table-condensed">
       <thead>
         <tr>
           <th class="text-center" style="width: 50px;">#</th>
           <th>Product Name</th>
           <th>Date</th>
           <th>Total Sale</th>
         </tr>
       </thead>
       <tbody>
         <?php foreach ($recent_sales as  $recent_sale): ?>
         <tr>
           <td class="text-center"><?php echo count_id();?></td>
           <td>
            <a href="edit_sale.php?id=<?php echo (int)$recent_sale['id']; ?>">
             <?php echo remove_junk(first_character($recent_sale['name'])); ?>
           </a>
           </td>
           <td><?php echo remove_junk(ucfirst($recent_sale['date'])); ?></td>
           <td>R.s <?php echo remove_junk(first_character($recent_sale['price'])); ?></td>
        </tr>

       <?php endforeach; ?>
       </tbody>
     </table>
    </div>
   </div>
  </div>
  <div class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Recently Added Products</span>
        </strong>
      </div>
      <div class="panel-body">

        <div class="list-group">
      <?php foreach ($recent_products as  $recent_product): ?>
            <a class="list-group-item clearfix" href="edit_product.php?id=<?php echo    (int)$recent_product['id'];?>">
                <h4 class="list-group-item-heading">
                 <?php if($recent_product['media_id'] === '0'): ?>
                    <img class="img-avatar img-circle" src="uploads/products/no_image.png" alt="">
                  <?php else: ?>
                  <img class="img-avatar img-circle" src="uploads/products/<?php echo $recent_product['image'];?>" alt="" />
                <?php endif;?>
                <?php echo remove_junk(first_character($recent_product['name']));?>
                  <span class="label label-warning pull-right">
                 R.s <?php echo (int)$recent_product['sale_price']; ?>
                  </span>
                </h4>
                <span class="list-group-item-text pull-right">
                <?php echo remove_junk(first_character($recent_product['categorie'])); ?>
              </span>
          </a>
      <?php endforeach; ?>
    </div>
  </div>
 </div>
</div>
 </div>
  <div class="row">

  </div>

<?php include_once('layouts/footer.php'); ?>