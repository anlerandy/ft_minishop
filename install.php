<?php
	require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/users.php";
	require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/items.php";
	require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/categories.php";
	require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/baskets.php";

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
	echo "Installation des tables :<br/>Utilisateurs";
	create_user_table($db);
	echo " : Ok<br/>Produits";
	create_item_table($db);
	echo " : Ok<br/>Catégories";
	create_category_table($db);
	echo " : Ok<br/>Paniers";
	create_basket_table($db);
	echo " : Ok<br/><br/>Redirection...";
	header( "refresh:5;url=/" );

	// Disconnect from the database
	mysqli_close($db);
?>
