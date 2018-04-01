<?php
require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/config.php";

function create_basket_table($db)
{
	if (!mysqli_query($db,
		"CREATE TABLE IF NOT EXISTS baskets (
			id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			user INT UNSIGNED,
			items TEXT,
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

	// Select the minishop database
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
	$serialized_items = base64_encode(serialize($_SESSION["basket"]));
	if (!mysqli_stmt_bind_param($query, "iss", $_SESSION["user_id"], $serialized_items, $status))
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

function query_archived_baskets()
{
	require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/config.php";

	// Connect to the SQL server
	$db = mysqli_connect($db_server, $db_user, $db_password);
	if (!$db)
	{
		header("Location: /includes/error.php?error=connection_to_database_failed" . $db_server . $db_user . $db_password);
		exit (1);
	}

	// Select the minishop database
	if (!mysqli_select_db($db, $db_name))
	{
		header("Location: /includes/error.php?error=database_selection_failed");
		exit (1);
	}

	// Prepare the query
	$query = mysqli_prepare($db, "SELECT id FROM baskets WHERE user=? AND status='archived'");
	if (!$query)
	{
		header("Location: /includes/error.php?error=query_preparation_failed");
		exit (1);
	}

	// Bind the parameters
	if (!mysqli_stmt_bind_param($query, "i", $_SESSION["user_id"]))
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
	if (!mysqli_stmt_bind_result($query, $basket_id))
	{
		header("Location: /includes/error.php?error=bind_result_failed");
		exit (1);
	}

	// Get the results
	$baskets = [];
	while (mysqli_stmt_fetch($query))
	{
		$baskets[] = ["id" => $basket_id];
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

	return $baskets;
}

function unarchive_basket()
{
	require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/config.php";

	if (!isset($_POST["basket_id"]))
	{
		header("Location: /includes/error.php?error=invalid_unarchiving_form");
		exit (1);
	}

	// Connect to the SQL server
	$db = mysqli_connect($db_server, $db_user, $db_password);
	if (!$db)
	{
		header("Location: /includes/error.php?error=connection_to_database_failed" . $db_server . $db_user . $db_password);
		exit (1);
	}

	// Select the minishop database
	if (!mysqli_select_db($db, $db_name))
	{
		header("Location: /includes/error.php?error=database_selection_failed");
		exit (1);
	}

	// Prepare the query
	$query = mysqli_prepare($db, "SELECT items FROM baskets WHERE id=?");
	if (!$query)
	{
		header("Location: /includes/error.php?error=query_preparation_failed");
		exit (1);
	}

	// Bind the parameters
	if (!mysqli_stmt_bind_param($query, "i", $_POST["basket_id"]))
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
	if (!mysqli_stmt_bind_result($query, $basket_items))
	{
		header("Location: /includes/error.php?error=bind_result_failed");
		exit (1);
	}

	// Get the result and add it to the session basket
	if (!mysqli_stmt_fetch($query))
	{
		unset($_SESSION);
		header("Location: /includes/error.php?error=query_fetching_failed");
		exit (1);
	}
	$_SESSION["basket"] = unserialize(base64_decode($basket_items));

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

	// Delete the archived basket
	require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/delete_function.php";
	delete_from_table("baskets", $_POST["basket_id"]);
}
?>
