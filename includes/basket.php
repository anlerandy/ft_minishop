<?php
	session_start();
?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="/css/default.css" />
		<title>Minishop - Mon panier</title>
	</head>

	<body>
		<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/menu.html"; ?>

		<?php if (isset($_SESSION["basket"])) { ?>
			<!-- List of chosen items -->
			<div id="chosen-items">
				<?php
					require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/items.php";
					$basket = isset($_SESSION["basket"]) ? $_SESSION["basket"] : []; // Use an empty array is no basket exists yet
					foreach ($basket as $id => $quantity)
					{
						$item = query_item($id);
				?>
						<div class="chosen-item">
							<?php echo "<img src=\"" . $item["image"] . "\" />"; ?>
							<b><?php echo $item["name"] ?></b> <br />
							<?php echo $item["price"] ?> &euro; <br />
							Quantit&eacute;: <?php echo $quantity ?> <br />
						</div>
				<?php } ?>
			</div>

			<?php if (isset($_SESSION["logged_in_user"])) { ?>
				<!-- Validation button -->
				<form action="/includes/validate_command.php">
					<input type="submit" name="validate" value="Valider la commande">
				</form>

				<!-- Archiving button -->
				<form action="/includes/archive_basket.php">
					<input type="submit" name="archive" value="Archiver mon panier">
				</form>
			<?php } else { ?>
				<b><a href="/includes/users/signin.php">Inscrivez-vous pour commander ou archiver votre panier !</a></b>
			<?php } ?>
		<?php } else { ?>
			Vous n'avez aucun produit dans votre panier. <br />
			<b><a href="/includes/products.php">Cliquez ici pour choisir des produits !</a></b>
		<?php } ?>
	</body>
</html>
