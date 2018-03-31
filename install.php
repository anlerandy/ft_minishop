<?php
	require "includes/database/users.php";
	require "includes/database/items.php";
	require "includes/database/categories.php";

	// Connect to the SQL server
	$db = mysqli_connect($db_server, $db_user, $db_password);
	if (!$db)
		exit ("ERROR: " . mysqli_connect_error());



	// Select or create the database
	if (!mysqli_select_db($db, $db_name))
	{
		// Create the database
		if (mysqli_query($db, "CREATE DATABASE " . $db_name))
			echo "Database \"" . $db_name . "\" created successfully<br />";
		else
			exit ("ERROR: " . mysqli_error($db));

		// Select the database
		if (!mysqli_select_db($db, $db_name))
			exit ("ERROR: " . mysqli_connect_error());
	}
	else
		echo "Database \"" . $db_name . "\" already exists<br />";

	// Create tables
	create_user_table($db);
	create_item_table($db);
	create_category_table($db);

	// Disconnect from the database
	mysqli_close($db);
?>
