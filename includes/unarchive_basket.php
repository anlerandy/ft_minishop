<?php
	session_start();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="/css/default.css" />
		<title>Minishop - Chargement du panier</title>
	</head>
	<body>
		<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/menu.html"; ?>

		Veuillez patienter...

		<?php
			require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/baskets.php";
			unarchive_basket();
		?>
	</body>
</html>
