<?php
function create_item_table($db)
{
	if (!mysqli_query($db,
		"CREATE TABLE IF NOT EXISTS items (
			id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			name varchar(255) UNIQUE,
			categories BLOB,
			image TEXT,
			price DECIMAL(10, 2)
		)"
	))
		exit ("ERROR: " . mysqli_error($db));
}

function query_item($id)
{
	require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/config.php";

	// Connect to the SQL server
	$db = mysqli_connect($db_server, $db_user, $db_password);
	if (!$db)
	{
		header("Location: /includes/error.php?error=connection_to_database_failed_" . $db_server . $db_user . $db_password);
		exit (1);
	}

	// Select the item database
	if (!mysqli_select_db($db, $db_name))
	{
		header("Location: /includes/error.php?error=database_selection_failed");
		exit (1);
	}

	// Prepare the query
	$query = mysqli_prepare($db, "SELECT name, categories, image, price FROM items WHERE id=?");
	if (!$query)
	{
		header("Location: /includes/error.php?error=query_preparation_failed");
		exit (1);
	}

	// Bind the id parameter (the ?)
	if (!mysqli_stmt_bind_param($query, "i", $id))
	{
		header("Location: /includes/error.php?error=bind_param_failed");
		exit (1);
	}

	// Execute the query
	if (!mysqli_stmt_execute($query))
	{
		header("Location: /includes/error.php?error=query_execution_failed");
		exit (1);
	}

	// Bind the results
	if (!mysqli_stmt_bind_result($query, $item_name, $item_categories, $item_image, $item_price))
	{
		header("Location: /includes/error.php?error=bind_result_failed");
		exit (1);
	}

	// Get the results
	if (!mysqli_stmt_fetch($query))
	{
		unset($_SESSION);
		header("Location: /includes/error.php?error=query_fetching_failed");
		exit (1);
	}

	// Close the query
	if (!mysqli_stmt_close($query))
	{
		header("Location: /includes/error.php?error=query_closing_failed");
		exit (1);
	}

	// Disconnect from the SQL server
	if (!mysqli_close($db))
	{
		header("Location: /includes/error.php?error=database_closing_failed");
		exit (1);
	}

	if (!isset($item_image))
		$item_image = "https://abtsmoodle.org/abtslebanon.org/wp-content/uploads/2017/10/image_unavailable.jpg";

	return ["name" => $item_name, "categories" => $item_categories, "image" => $item_image, "price" => $item_price];
}

function query_all_items()
{
	require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/config.php";

	// Connect to the SQL server
	$db = mysqli_connect($db_server, $db_user, $db_password);
	if (!$db)
	{
		header("Location: /includes/error.php?error=connection_to_database_failed_" . $db_server . $db_user . $db_password);
		exit (1);
	}

	// Select the item database
	if (!mysqli_select_db($db, $db_name))
	{
		header("Location: /includes/error.php?error=database_selection_failed");
		exit (1);
	}

	$query = mysqli_query($db, "SELECT * FROM items");
	if (!$query)
	{
		header("Location: /includes/error.php?error=query_failed");
		exit (1);
	}

	// Fetch the items
	$items = [];
	while ($item = mysqli_fetch_assoc($query))
	{
		if (!isset($item["image"]))
			$item["image"] = "https://abtsmoodle.org/abtslebanon.org/wp-content/uploads/2017/10/image_unavailable.jpg";

		$items[] = $item;
	}

	// Disconnect from the SQL server
	if (!mysqli_close($db))
	{
		header("Location: /includes/error.php?error=database_closing_failed");
		exit (1);
	}

	return $items;
}
?>
