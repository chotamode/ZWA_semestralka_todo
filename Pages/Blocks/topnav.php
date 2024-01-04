<link rel="stylesheet" href="../CSS/topnav.css">
<div class="topnav">
  <?php

    require_once '../Service/AuthService.php';
    $authService = new AuthService();

    if (isset($_COOKIE['user_id'])) {
      echo '<a id="topnav_profile" class="topnav_button" href="../Pages/profile.php"><img src="../Images/profile.svg" alt="Profile" class="topnav_icon"></a>';
      echo '<a id="topnav_tasks" class="topnav_button" href="../Pages/tasks.php"><img src="../Images/tasks.svg" alt="Tasks" class="topnav_icon"></a>';
      echo '<a id="topnav_projects" class="topnav_button" href="../Pages/projects.php"><img src="../Images/projects.svg" alt="Projects" class="topnav_icon"></a>';
      echo '<form id="topnav_logout" class="topnav_button" action="../Controller/AuthController.php?action=logout" method="POST"><input type="submit" value="Logout"></form>';
      // if user admin or owner show admin button
      if ($authService->isUserRoleAdmin($_COOKIE['user_id'])) {
        echo '<a id="topnav_admin" class="topnav_button" href="../Pages/admin.php"><img src="../Images/admin.svg" alt="Admin" class="topnav_icon"></a>';
      }
    } else {
      echo '<a id="topnav_home" class="topnav_button" href="../index.php">Home</a>';
      echo '<a id="topnav_login" class="topnav_button" href="../Pages/login.php">Login</a>';
      echo '<a id="topnav_register" class="topnav_button" href="../Pages/register.php">Register</a>';
    }
  ?>
</div>