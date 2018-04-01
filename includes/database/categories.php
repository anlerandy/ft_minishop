<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/database/config.php";

function create_category_table($db)
{
	if (!mysqli_query($db,
		"CREATE TABLE IF NOT EXISTS categories (
			id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			name TEXT
		)"
	))
		exit ("ERROR: " . mysqli_error($db));
}

function query_all_categories()
{
	require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/config.php";

	// Connect to the SQL server
	$db = mysqli_connect($db_server, $db_user, $db_password);
	if (!$db)
	{
		header("Location: /includes/error.php?error=connection_to_database_failed_" . $db_server . $db_user . $db_password);
		exit (1);
	}

	// Select the minishop database
	if (!mysqli_select_db($db, $db_name))
	{
		header("Location: /includes/error.php?error=database_selection_failed");
		exit (1);
	}

	$query = mysqli_query($db, "SELECT * FROM categories");
	if (!$query)
	{
		header("Location: /includes/error.php?error=query_failed");
		exit (1);
	}

	// Fetch the categories
	$categories = [];
	while ($category = mysqli_fetch_assoc($query))
		$categories[] = $category;

	// Disconnect from the SQL server
	if (!mysqli_close($db))
	{
		header("Location: /includes/error.php?error=database_closing_failed");
		exit (1);
	}

	return $categories;
}
?>
