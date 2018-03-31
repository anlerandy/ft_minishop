<?php
	session_start();

	require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/database/items.php";

	// !!!
	$_SESSION["basket"] = [0 => 1, 1 => 1, 2 => 2];
?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
		<title>Minishop - Mon panier</title>
		<style>
			#chosen-items {
				display:flex;
			}

			.chosen-item {
				width: 200px;
				background: lightgray;
				margin: 5px;
				display:flex;
				flex-direction: column;
			}

			.chosen-item img {
				max-width: 100%;
			}

			.chosen-item b {
				text-align: center;
			}
		</style>
	</head>

	<body>
		<!-- List of chosen items -->
		<div id="chosen-items">
			<?php
				foreach ($_SESSION["basket"] as $id => $quantity)
				{
					$item = query_item($id);
			?>
				<div class="chosen-item">
					<img src="https://image.afcdn.com/recipe/20161130/34577_w600.jpg" />
					<b><?php echo $item["name"] ?></b> <br />
					item price <br />
					item number <br />
				</div>
			<?php } ?>
		</div>

		<!-- Validation button -->
	</body>
</html>
