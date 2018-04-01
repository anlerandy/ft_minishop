<?php
session_start();
require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/users.php";

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

//take the categorie
  $requete_get_cat = mysqli_prepare($db, "SELECT * FROM categories WHERE 1");
  if (mysqli_stmt_execute($requete_get_cat))
  {
    mysqli_stmt_bind_result($requete_get_cat, $id, $name);
    while(mysqli_stmt_fetch($requete_get_cat))
      $tab[$id] = $name ;
  }
  if (!isset($tab))
  $tab = array();
?>
<html>
<head>
	<meta charset="UTF-8" />
	<link rel="stylesheet" type="text/css" href="/css/default.css" />
</head>
<body>
	<?php
	require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/menu.html";
  $cat = array();

	// Petite vérification de ce qui se trouve dans nos deux arrays
if (isset($_POST))
{
  if (isset($_POST['cat']))
  {
    $_POST['cat'] = serialize($_POST['cat']);
      if (!($requete_ins = mysqli_prepare($db, "INSERT INTO items (id, name, categories, image, price) VALUES (null, ?, ?, ?, ?)")))
        exit (mysqli_error($db));
        mysqli_stmt_bind_param($requete_ins, "ssss", $_POST['name'], $_POST['cat'], $_POST['image'], $_POST['price']);
  if (mysqli_stmt_execute($requete_ins))
        echo mysqli_stmt_error($requete_ins);
  else
  {
    mysqli_prepare($db, ($tmp = sprintf("SELECT id FROM items WHERE name=%s", $_POST['name'])));
    if (mysqli_stmt_execute($tmp))
    {
      mysqli_stmt_bind_result($tmp, $id, $name);
      while(mysqli_stmt_fetch($tmp))
      $id = $name;
    }
    header("Location: /includes/admin/?produit=".$id);
  }
  }
}
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
      <div id="add_container">
        <form method="POST" action="new_prod.php">
        Name : <input name="name" />
        Image : <input name="image"/>
        Catégories :<br/>
        <?php foreach($tab as $key => $val): ?>
          <input type="checkbox" name="cat[]" value=<?=$val?>><?=$val?></input>
        <?php endforeach; ?>
        Prix : <input name="price" />
        <button name="redirect" value="">Créer</button>
      </form>
      </div>
</div>
</div>
</body>
</html>
