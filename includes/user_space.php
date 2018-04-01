<?php
	session_start();

	if (!isset($_SESSION["logged_in_user"]))
	{
		header("Location: /index.php");
		exit;
	}
?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="/css/default.css" />
		<title>Minishop - Espace utilisateur</title>
	</head>
	<body>
		<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/menu.html"; ?>

		<?php
			require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/baskets.php";
			$baskets = query_archived_baskets();
			foreach ($baskets as $basket)
			{
		?>
				<div class="archived-basket">
					<img src="https://www.ldoceonline.com/media/english/illustration/basket.jpg" alt="Panier">
					DATE
					<form action="/includes/unarchive_basket.php" method="post">
						<button type="submit" name="basket_id" value=<?=$basket["id"]?>>Recharger ce panier</button>
					</form>
				</div>
		<?php } ?>
	</body>
</html>
