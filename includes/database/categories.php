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
?>
