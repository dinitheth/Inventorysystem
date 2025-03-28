<?php
  ob_start();
  require_once('includes/load.php');
  if($session->isUserLoggedIn(true)) { redirect('home.php', false);}
?>
<?php include_once('layouts/header.php'); ?>


<style>
  /* Body styling for centering content */
  body {
    font-family: 'Arial', sans-serif;
    background-color:rgb(89, 186, 250);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
  }

  /* Login panel styling */
  .login-page {
    background-color: #fff;
    padding: 150px 75px;
    border-radius: 10px;
    box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
    width: 200%;
    max-width: 475px;
    text-align: center;
  }

  /* Title and subheading */
  .login-page h1 {
    font-size: 35px;
    font-weight: bold;
    color: #333;
    margin-bottom: 15px;
    
  }

  .login-page h4 {
    font-size: 18px;
    color: #888;
    margin-bottom: 30px;
  }

  /* Input fields styling */
  .form-group {
    margin-bottom: 20px;
    position: relative;
  }

  .form-group label {
    font-size: 14px;
    color: #555;
    position: absolute;
    top: -10px;
    left: 15px;
    background-color: #fff;
    padding: 0 5px;
    font-weight: bold;
  }

  .form-control {
    width: 100%;
    padding: 15px;
    border-radius: 6px;
    border: 1px solid #ddd;
    font-size: 16px;
    background-color: #f8f8f8;
    transition: all 0.3s ease;
    margin-bottom: 15px;
  }

  .form-control:focus {
    border-color: #4fa3fe;
    box-shadow: 0 0 8px rgba(79, 163, 254, 0.4);
    outline: none;
  }

  /* Submit button */
  button[type="submit"] {
    width: 100%;
    padding: 15px;
    background-color: #4fa3fe;
    border: none;
    border-radius: 6px;
    color: #fff;
    font-size: 18px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  button[type="submit"]:hover {
    background-color: #00c7ff;
  }

  /* Message display styling */
  .msg {
    margin: 10px 0;
    padding: 10px;
    background-color: #ffcc00;
    color: #fff;
    border-radius: 6px;
    font-size: 14px;
  }
</style>

<div class="login-page">
    <div class="text-center">
       <h1>KA ELECTRICALS</h1>
       <h4>Inventory Management System</h4>
     </div>
     <?php echo display_msg($msg); ?>
      <form method="post" action="auth.php" class="clearfix">
        <div class="form-group">
              <label for="username" class="control-label">Username</label>
              <input type="name" class="form-control" name="username" required>
        </div>
        <div class="form-group">
            <label for="Password" class="control-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
                <button type="submit" class="btn btn-danger" style="border-radius:0%">Login</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>
