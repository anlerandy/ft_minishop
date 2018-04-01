<?php
session_start();
require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/users.php";
	// Connect to the SQL server
	$db = mysqli_connect($db_server, $db_user, $db_password);
	if (!$db)
		exit ("ERROR: " . mysqli_connect_error());
	// Create the database if it doesn't exist already
	if (!mysqli_select_db($db, $db_name))
	{		header('Location: /includes/_install.php');
	exit(0);}
?>
<html>
<head>
	<meta charset="UTF-8" />
	<link rel="stylesheet" type="text/css" href="css/default.css" />
</head>
<body>
	<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/menu.html"; ?>

	// List of a few random items
	<div id="chosen-items">
		<?php
			require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/database/items.php";
			$items = query_all_items("ORDER BY RAND() LIMIT 3");
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
