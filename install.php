<?php
	$server = "localhost";
	$user = "root";
	$password = "alerandy";
	$mydb = "minishop";

	$conn = mysqli_connect($server, $user, $password);
	if (!$conn)
	{
		echo "ERROR\n". mysqli_connect_error();
	}
	else
	{
		$sql = "CREATE DATABASE IF NOT EXISTS minishop";
		if (mysqli_slect_db($conn, $mydb))
			echo "Databse Minishop Already exist";
		else if (mysqli_query($conn, $sql))
			echo "Database Minishop created successfully";
		else
		{
			echo "ERROR\n". mysqli_error($conn);
			exit (1);
		}
	}
?>
