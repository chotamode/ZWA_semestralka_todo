<?php

    // unset cookie
    setcookie('user_id', '', time() - 3600, "/");

    // Redirect to home page
    header('Location: ../index.php');

?>