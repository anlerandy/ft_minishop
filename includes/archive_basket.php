<?php
	session_start();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="/css/default.css" />
		<title>Minishop - Archivage du panier</title>
	</head>
	<body>
		<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/menu.html"; ?>

		Votre panier a &eacute;t&eacute; archiv&eacute; ! <br />
		<b><a href="/index.php">Retourner au menu</a></b>

		<?php
			require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/baskets.php";
			archive_basket("archived");

			// Vider le panier
			unset($_SESSION["basket"]);
		?>
	</body>
</html>
