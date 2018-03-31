<?php
require_once "/includes/database/config.php";;

function create_basket_table($db)
{
	if (!mysqli_query($db,
		"CREATE TABLE IF NOT EXISTS baskets (
			id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			user INT UNSIGNED,
			items TEXT
		)"
	))
		exit ("ERROR: " . mysqli_error($db));
}
?>
