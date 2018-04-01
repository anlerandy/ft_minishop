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
				$items = query_all_items("");
				if (count($items))
				{
					if (isset($_GET["category"]))
						$category_filter = $_GET["category"];
					else
						$category_filter = "all";
			?>
					<!-- Category filter -->
					<form action="/includes/products.php" method="get" id="category_filter">
						<select name="category" form="category_filter" onchange="this.form.submit()">
							<option value="all">Toutes les cat&eacute;gories</option>
							<?php
								require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/categories.php";
								$categories = query_all_categories();
								foreach ($categories as $category)
								{
							?>
								<option value="<?= $category["id"] ?>" <?php if ($category["id"] == $category_filter) echo "selected" ?>><?= $category["name"] ?></option>
							<?php } ?>
						</select>
					</form>
			<?php
					foreach ($items as $item)
					{
						if ($category_filter === "all" || in_array($category_filter, $item["categories"]))
						{
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
			<?php
						}
					}
				}
				else {
			?>
					Nous sommes d&eacute;sol&eacute;s, il n'y a aucun produit en stock pour le moment.
			<?php } ?>
		</div>
	</body>
</html>
