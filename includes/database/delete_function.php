<?php
function delete_from_table($table, $id)
{
	require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/config.php";

	if (!isset($id))
	{
		header("Location: /includes/error.php?error=missing_id_for_deletion");
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
	$query = mysqli_prepare($db, "DELETE FROM " . $table . " WHERE id=?");
	if (!$query)
	{
		header("Location: /includes/error.php?error=query_preparation_failed");
		exit (1);
	}

	// Bind the parameters
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
