<div class="topnav">
  <?php

  /**
   * topnav.php
   * 
   * This file represents a top navigation bar. It is included in every page.
   * 
   * @category Blocks
   * @package  Blocks
   */

  $current_page = basename($_SERVER['PHP_SELF']);

  if ($current_page != 'index.php') {
    require_once '../Service/AuthService.php';
    $authService = new AuthService();
}

  if (isset($_COOKIE['user_id'])) {
    echo '<a id="topnav_profile" class="topnav_button" href="../Pages/profile.php"><img src="../Images/profile.svg" alt="Profile" class="topnav_icon"></a>';
    echo '<a id="topnav_tasks" class="topnav_button" href="../Pages/tasks.php"><img src="../Images/tasks.svg" alt="Tasks" class="topnav_icon"></a>';
    echo '<a id="topnav_projects" class="topnav_button" href="../Pages/projects.php"><img src="../Images/projects.svg" alt="Projects" class="topnav_icon"></a>';
    echo '<form id="topnav_logout" class="topnav_button" action="../Controller/AuthController.php?action=logout" method="POST"><button type="submit" id="logout_button"><img src="../Images/logout.svg" alt="Logout" class="topnav_icon"></button></form>';
    // if user admin or owner show admin button
    if ($current_page != 'index.php' && $authService->isUserRoleAdmin($_COOKIE['user_id'])) {
      echo '<a id="topnav_admin" class="topnav_button" href="../Pages/admin.php"><img src="../Images/admin_button.svg" alt="Admin" class="topnav_icon"></a>';
    }
    // if user owner show owner button
    if ($current_page != 'index.php' && $authService->isUserRoleOwner($_COOKIE['user_id'])) {
      echo '<a id="topnav_owner" class="topnav_button" href="../Pages/owner.php"><img src="../Images/owner_button.svg" alt="Owner" class="topnav_icon"></a>';
    }
  } else {
    echo '<a id="topnav_home" class="topnav_button" href="../index.php"><img src="../Images/home.svg" alt="Home" class="topnav_icon"></a>';
    echo '<a id="topnav_login" class="topnav_button" href="../Pages/login.php"><img src="../Images/login.svg" alt="Login" class="topnav_icon"></a>';
    echo '<a id="topnav_register" class="topnav_button" href="../Pages/register.php"><img src="../Images/register.svg" alt="Register" class="topnav_icon"></a>';
  }
  ?>
</div>