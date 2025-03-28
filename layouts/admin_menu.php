<link rel="stylesheet" href="libs/css/pop.css">

<ul style="font-size: 16px;">
  <li>
    <a href="admin.php">
      <i class="glyphicon glyphicon-home" style="font-size: 18px;"></i>
      <span style="font-size: 16px;">Dashboard</span>
    </a>
  </li>
  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-credit-card" style="font-size: 18px;"></i>
      <span style="font-size: 16px;">Sales</span>
    </a>
    <ul class="nav submenu">
      <li><a href="add_sale.php">Add Sale</a></li>
      <li><a href="sales.php">Manage Sales</a></li>
    </ul>
  </li>
  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-th-large" style="font-size: 18px;"></i>
      <span style="font-size: 16px;">Products</span>
    </a>
    <ul class="nav submenu">
      <li><a href="add_product.php">Add Products</a></li>
      <li><a href="product.php">Manage Products</a></li>
      <li><a href="categorie.php">Categories</a></li>
    </ul>
  </li>
  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-user" style="font-size: 18px;"></i>
      <span style="font-size: 16px;">Customers</span>
    </a>
    <ul class="nav submenu">
      <li><a href="add_customer.php">Add Customer</a></li>
      <li><a href="customers_list.php">Customers List</a></li>
    </ul>
  </li>
  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-share-alt" style="font-size: 18px;"></i>
      <span style="font-size: 16px;">Return</span>
    </a>
    <ul class="nav submenu">
      <li><a href="return_sale.php">Add Sales Return</a></li>
      <li><a href="sales_returns.php">View Sales Returnlist</a></li>
      <li><a href="purchase_return.php">Add Purchase Return</a></li>
      <li><a href="purchase_returns_history.php">View Purchase Returnlist</a></li>
    </ul>
  </li>
  <!-- New Backup Menu -->
  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-floppy-save" style="font-size: 18px;"></i>
      <span style="font-size: 16px;">Backup & Restore</span>
    </a>
    <ul class="nav submenu">
      <li><a href="backup.php">Database Backup</a></li>
      <li><a href="restore.php">Restore Backup</a></li>
    </ul>
  </li>
  <li>
    <a href="#" class="submenu-toggle" id="userManagementLink">
      <i class="glyphicon glyphicon-user" style="font-size: 18px;"></i>
      <span style="font-size: 16px;">Manage Users</span>
    </a>
    <ul class="nav submenu">
      <li><a href="#" onclick="restrictAccess('Users')">Manage Users</a></li>
    </ul>
  </li>
  <li>
    <a href="#" class="submenu-toggle" id="reportsLink">
      <i class="glyphicon glyphicon-duplicate" style="font-size: 18px;"></i>
      <span style="font-size: 16px;">Reports</span>
    </a>
    <ul class="nav submenu">
      <li><a href="#" onclick="restrictAccess('Sales Report')">Sales by dates</a></li>
    </ul>
  </li>
</ul>

<!-- Restricted Access Popup -->
<div id="restrictedPopup" class="popup">
  <div class="popup-content">
    <h2>Restricted Access</h2>
    <p>You are trying to access: <span id="sectionName"></span></p>
    <label for="adminPassword">Please enter the Admin password to access this section:</label>
    <input type="password" id="adminPassword" placeholder="Enter password">
    <button id="submitPassword">OK</button>
    <button id="cancelPopup">Cancel</button>
  </div>
</div>

<script src="libs/js/pop.js"></script>