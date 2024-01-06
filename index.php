<?php
	/**
	 * index.php
	 * 
	 * This file represents the main page of the application. It is used to display information about the application.
	 * 
	 */

	ini_set('display_errors', 1); 
	ini_set('display_startup_errors', 1); 
	error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="CSS/index.css">
		<link rel="stylesheet" href="CSS/topnav.css">
		<title>Todo List</title>
	</head>
	<body>
		<?php require_once 'Pages/Blocks/topnav.php' ?>

		<div id="home_page_info">
			<h1>Welcome to the Todo List Application!</h1>
			<p>This is the main page of the application where users can view the todo list and register to start managing their tasks.</p>
			<p>To get started, please click on the "Register" button.</p>
		</div>

		

	</body>
</html>
