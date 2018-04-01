<?php
session_start();
require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/users.php";
require $_SERVER["DOCUMENT_ROOT"] . "/includes/admin/show_cat_all.php";
require $_SERVER["DOCUMENT_ROOT"] . "/includes/admin/show_onecat.php";
require $_SERVER["DOCUMENT_ROOT"] . "/includes/admin/show_prod.php";

// Vérification qu'un utilisateur est connecté ET qu'il est admin. Redirigé en index sinon.
if (isset($_SESSION['logged_in_user']))
{
	if (!isset($_SESSION['level_user']) || $_SESSION['level_user'] !== '1')
		header("Location: /");
}
else
	header("Location: /");
// Connect to the SQL server
$db = mysqli_connect($db_server, $db_user, $db_password);
if (!$db)
exit ("ERROR: " . mysqli_connect_error());
// Create the database if it doesn't exist already
if (!mysqli_select_db($db, $db_name))
header('Location: /includes/_install.php');
?>
<html>
<head>
	<meta charset="UTF-8" />
	<link rel="stylesheet" type="text/css" href="/css/default.css" />
</head>
<body>
	<?php
	require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/menu.html";

	// Petite vérification de ce qui se trouve dans nos deux arrays
	if (isset($_GET))
		print_r($_GET);
	if (isset($_POST))
		print_r($_POST);
	?>
	<div id="wrap">
		<div id="sidebar">
			Manage :<br/>
			<a href="/includes/admin?p=cat">Catégories -></a><br/>
			<a href="/includes/admin?p=prod">Produits -></a><br/>
			<a href="/includes/admin?p=user">Utilisateurs -></a><br/>
			<a href="/includes/admin?p=basket">Paniers -></a><br/>
		</div>
		<div id="frame">
				<?php

				// Vérification des arrays pour set une page grâce aux fonctions.
				if (isset($_GET['p']) || isset($_GET['categorie']) || isset($_GET['produit']))
				{
								if (isset($_GET['p']) && $_GET['p'] === 'cat')
									show_cat_all();
								if (isset($_GET['p']) && $_GET['p'] === 'prod')
								{
							  	echo "<div  id=\"container\"><h2>Liste des articles :</h2>";
									echo "<form method=\"GET\" action=\"/includes/database/new_prod.php\"><button id=\"none\" > Créer un noueau produit</button></form>";
									show_prod(NULL, NULL, NULL);
								}
								if (isset($_GET['categorie']))
									show_onecat($_GET['categorie']);
								if (isset($_GET['produit']))
								{
							  	echo "<div  id=\"container\"><h2>Modifier l'article :</h2>";
									$tab = show_prod('id', $_GET['produit'], NULL);
									echo "<h4>ID de l'article : ".$_GET['produit']."</h4><br/>
								  <form method=\"POST\" action=\"index.php?produit=".$_GET['produit']."\">
								  <input placeholder=\"Nouveau nom de l'article P. Ex. : ".$tab[$_GET['produit']]['name']."\" name=\"newname\" />
								  <input placeholder=\"Nouveau prix\" name=\"price\" />
								  <input placeholder=\"Nouvel url image P. Ex. : ".$tab[$_GET['produit']]['img']."\" name=\"image\" />
								  <button name=\"submit\" value=\"submited\">Nouveau nom</button>
								  </form>
								<form method=\"POST\" action=\"delete.php?categorie=".$_GET['produit']."\">
								<button name=\"submit\" value=\"submited\">Supprimer le produit</button>
								</form>
								    </div><hr/>";
								}
				}
				else
				 	echo "Bienvenue sur la page d'admin : ".$_SESSION['logged_in_user'].",<br/>Sélectionner les éléments que vous voulez modifier.";
				?>
		</div>
	</div>
</body>
</html>
