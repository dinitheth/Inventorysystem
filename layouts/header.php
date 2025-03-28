<?php $user = current_user(); ?>
<!DOCTYPE html>
  <html lang="en">
    <head>
    <meta charset="UTF-8">
    <title><?php if (!empty($page_title))
           echo remove_junk($page_title);
            elseif(!empty($user))
           echo ucfirst($user['name']);
            else echo "Inventory Management System";?>
    </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
    <link rel="stylesheet" href="libs/css/main.css" />
    <link rel="stylesheet" href="libs/css/notification.css" />
  </head>
  <body>
  <?php  if ($session->isUserLoggedIn(true)): ?>
    <header id="header">
      <div class="logo pull-left" style="font-size: 20px;">K A Electricals</div>
      <div class="header-content">
      <div class="header-date pull-left">
        <strong id="dynamic-datetime" style="color: black; font-size: 16px;"></strong>
      </div>
      <div class="pull-right clearfix">
        <ul class="info-menu list-inline list-unstyled">
        <li class="profile">
            <a aria-expanded="false">
              <span><?php echo remove_junk(ucfirst($user['name'])); ?> <i class="caret"></i></span>
            </a>
        </li>
        <li class="last">
      <a href="logout.php" style="color: red; font-weight: bold;">
      <i class="glyphicon glyphicon-off"></i>
       Logout
      </a>
        </li>
          <li class="notifications">
            <?php 
            $low_stock_count = count_low_stock();
            ?>
            <a href="Notification.php" class="notification-link">
              <i class="glyphicon glyphicon-bell"></i>
              <?php if($low_stock_count > 0): ?>
                <span class="badge notification-badge"><?php echo $low_stock_count; ?></span>
              <?php endif; ?>
            </a>
          </li>
        </ul>
      </div>
     </div>
    </header>
    <div class="sidebar">
      <?php if($user['user_level'] === '1'): ?>
        <!-- admin menu -->
      <?php include_once('admin_menu.php');?>

      <?php elseif($user['user_level'] === '2'): ?>
        <!-- Special user -->
      <?php include_once('special_menu.php');?>

      <?php elseif($user['user_level'] === '3'): ?>
        <!-- User menu -->
      <?php include_once('user_menu.php');?>

      <?php endif;?>

   </div>
<?php endif;?>

<div class="page">
  <div class="container-fluid">

<!-- JavaScript for dynamic date and time update in IST -->
<script>
  // Function to update the date and time in IST
  function updateDateTime() {
    const datetimeElement = document.getElementById('dynamic-datetime');
    if (!datetimeElement) return; // Safety check
    
    // Get the current time
    const now = new Date();
    
    // Format for IST (UTC+5:30)
    // Convert to IST - this is the most reliable method for showing IST time
    const options = { 
      timeZone: 'Asia/Kolkata',
      month: 'long',    // Full month name
      day: 'numeric',   // Day without leading zeros
      year: 'numeric',  // Full year
      hour: 'numeric',  // 12-hour format
      minute: '2-digit', // Minutes with leading zeros
      second: '2-digit', // Seconds with leading zeros
      hour12: true      // am/pm
    };
    
    // Format the date and time using the browser's built-in formatter with IST timezone
    const formattedDateTime = now.toLocaleString('en-US', options);
    
    // Update the content of the element
    datetimeElement.textContent = formattedDateTime;
  }
  
  // Run when DOM is loaded
  document.addEventListener('DOMContentLoaded', function() {
    // Update immediately
    updateDateTime();
    
    // Then update every second
    setInterval(updateDateTime, 1000);
  });
</script>