<?php
	session_start();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="/css/default.css" />
		<title>Minishop - Produits</title>
	</head>
	<body>
		<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/menu.html"; ?>

		<?php
			// If the user pressed the button, add the item to the basket
			if (isset($_POST) && isset($_POST["item"]))
			{
				// Create the basket if it's empty
				if (!isset($_SESSION["basket"]))
					$_SESSION["basket"] = [];

				// Add the item to the basket
				$id = $_POST["item"];
				if (!isset($_SESSION["basket"][$id]))
					$_SESSION["basket"][$id] = 1;
				else
					$_SESSION["basket"][$id] += 1;
			}
		?>

		<!-- List of chosen items -->
		<div id="chosen-items">
			<?php
				require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/database/items.php";
				$items = query_all_items();
				foreach ($items as $item) {
			?>
				<div class="chosen-item">
					<?php echo "<img src=\"" . $item["image"] . "\" />"; ?>
					<b><?php echo $item["name"] ?></b> <br />
					<?php echo $item["price"] ?> &euro; <br />

					<!-- Button to add to basket -->
					<form action="#" method="post">
						<button name="item" value=<?= $item["id"] ?>>Ajouter au panier</button>
					</form>
				</div>
			<?php } ?>
		</div>
	</body>
</html>
