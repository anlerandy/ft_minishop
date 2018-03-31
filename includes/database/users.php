<?php
require_once "config.php";

function create_user_table($db)
{
	if (!mysqli_query($db,
		"CREATE TABLE IF NOT EXISTS users (
			id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			name varchar(255) NOT NULL UNIQUE,
			password TEXT,
			admin BOOLEAN,
			UNIQUE (name)
		)"
	))
		exit ("ERROR: " . mysqli_error($db));
}
?>
