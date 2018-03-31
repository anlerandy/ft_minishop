<?php
session_start();
require "/includes/database/users.php";
	// Connect to the SQL server
	$db = mysqli_connect($db_server, $db_user, $db_password);
	if (!$db)
		exit ("ERROR: " . mysqli_connect_error());
	// Create the database if it doesn't exist already
	if (!mysqli_select_db($db, $db_name))
		header('Location: /includes/_install.php');
?>
<html>
<head>
	<meta charset="UTF-8" />
	<link rel="stylesheet" type="text/css" href="css/default.css" />
</head>
<body>
<?php
	require_once "includes/menu.html";
?>
</body>
</html>
