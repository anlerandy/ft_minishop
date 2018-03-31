<?php
	require "includes/database/users.php";

	// Connect to the SQL server
	$db = mysqli_connect($db_server, $db_user, $db_password);
	if (!$db)
		exit ("ERROR: " . mysqli_connect_error());



	// Create the database if it doesn't exist already
	if (!mysqli_select_db($db, $db_name))
	{
		// Create the database
		if (mysqli_query($db, "CREATE DATABASE " . $db_name))
			echo "Database \"" . $db_name . "\" created successfully<br />";
		else
			exit ("ERROR: " . mysqli_error($db));
	}
	else
		echo "Database \"" . $db_name . "\" already exists<br />";

	// Create tables
	create_user_table($db);

	// Disconnect from the database
	mysqli_close($db);
?>
