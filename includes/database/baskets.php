<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/database/config.php";

function create_basket_table($db)
{
	if (!mysqli_query($db,
		"CREATE TABLE IF NOT EXISTS baskets (
			id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			user INT UNSIGNED,
			items BLOB,
			status TEXT
		)"
	))
		exit ("ERROR: " . mysqli_error($db));
}

function archive_basket($status)
{
	require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/config.php";

	// if (!(isset($_SESSION["basket"]) && isset($_SESSION["logged_in_user"]) && isset($_SESSION["user_id"])))
	if (!(isset($_SESSION["basket"]) && isset($_SESSION["logged_in_user"])))
	{
		header("Location: /includes/error.php?error=invalid_archiving_form");
		exit (1);
	}

	// Connect to the SQL server
	$db = mysqli_connect($db_server, $db_user, $db_password);
	if (!$db)
	{
		header("Location: /includes/error.php?error=connection_to_database_failed" . $db_server . $db_user . $db_password);
		exit (1);
	}

	// Select the item database
	if (!mysqli_select_db($db, $db_name))
	{
		header("Location: /includes/error.php?error=database_selection_failed");
		exit (1);
	}

	// Prepare the query
	$query = mysqli_prepare($db, "INSERT INTO baskets (id, user, items, status) VALUES (null, ?, ?, ?)");
	if (!$query)
	{
		header("Location: /includes/error.php?error=query_preparation_failed");
		exit (1);
	}

	// Bind the parameters
	$serialized_items = serialize($_SESSION["basket"]);
	$_SESSION["user_id"] = 1; // !!! PLACEHOLDER
	if (!mysqli_stmt_bind_param($query, "ids", $_SESSION["user_id"], $serialized_items, $status))
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
}
?>
