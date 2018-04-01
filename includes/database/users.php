<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/database/config.php";

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
		$pass = hash("sha512", "admin");
		$requete_new_user = mysqli_prepare($db, "INSERT INTO users (id, name, password, admin) VALUES (null, 'admin', ?, '1')");
		mysqli_stmt_bind_param($requete_new_user, "s", $pass);
		mysqli_stmt_execute($requete_new_user);
		if (mysqli_stmt_error($requete_new_user))
			echo " : Admin already set";
}
?>
