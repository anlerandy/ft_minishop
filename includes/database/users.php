<?php
require_once "config.php";

function create_user_table($db)
{
	if (!mysqli_query($db,
		"CREATE TABLE IF NOT EXISTS users (
			id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			name TEXT,
			password TEXT,
			admin BOOLEAN
		)"
	))
		exit ("ERROR: " . mysqli_error($db));
}
?>
