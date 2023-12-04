<link rel="stylesheet" href="../CSS/topnav.css">
<div class="topnav">
  <?php
    if (isset($_COOKIE['user_id'])) {
      echo '<a id="topnav_profile" class="topnav_button" href="../Pages/profile.php">Profile</a>';
      echo '<a id="topnav_tasks" class="topnav_button" href="../Pages/tasks.php">Tasks</a>';
      echo '<a id="topnav_logout" class="topnav_button" href="../Service/logout.php">Logout</a>';
    } else {
      echo '<a id="topnav_home" class="topnav_button" href="../index.php">Home</a>';
      echo '<a id="topnav_login" class="topnav_button" href="../Pages/login.php">Login</a>';
      echo '<a id="topnav_register" class="topnav_button" href="../Pages/register.php">Register</a>';
    }
  ?>
</div>