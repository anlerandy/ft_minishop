<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/database/config.php";

function create_item_table($db)
{
	if (!mysqli_query($db,
		"CREATE TABLE IF NOT EXISTS items (
			id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			name TEXT,
			categories TEXT
		)"
	))
		exit ("ERROR: " . mysqli_error($db));
}
?>
